<?php
/**
 * Created by PhpStorm.
 * User: jesus
 * Date: 23/01/14
 * Time: 16:58
 */

namespace Mundoreader\CalendarBundle\Controller;

use Mundoreader\CalendarBundle\Entity\Day;
use Mundoreader\CalendarBundle\Entity\DayRepository;
use Mundoreader\CalendarBundle\Form\DayType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class DayController extends Controller
{

    public function createAction(Request $request)
    {
        // if we do not have uid cookie, do nothing
        $cookies = $request->cookies->all();
        if (empty($cookies)) {
            throw new Exception('You shall not pass!');
        } else {
            $cid = $cookies['cid'];
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Calendar');
            $calendar = $repository->findOneBy(array('calendarId' => $cid));
            $form = $this->createForm(new DayType($calendar), new Day());
            $form->handleRequest($request);
            if ($form->isValid()) {
                //  find day
                $em = $this->getDoctrine()->getManager();
                $receivedDate = $form->getData()->getdate();
                $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Day');
                $day = $form->getData();
                $user = $day->getUser();
                $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:User');
                $repository->clearUserId($user);
                $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Day');
                $repository->clearUserId($user);
                $day->setCalendar($calendar);
                $user->setDay($day);
                $em->persist($day);
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('mundoreader_calendar_homepage', array('calendarId' => $cid)));
            } else {
                throw new Exception('Invalid form');
            }
        }
    }

    public function getDayAction(Request $request)
    {
        $cookies = $request->cookies->all();
        if ($request->isXmlHttpRequest() && !empty($cookies)) {
            $date = str_replace('"', '', $request->request->get('date'));
            $datetime = new DateTime($date);
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Calendar');
            $calendar = $repository->findOneBy(array('calendarId' => $cookies['cid']));
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:User');
            $user = $repository->findOneBy(array('uid' => $cookies['uid']));
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Day');
            $day = $repository->findOneBy(array(
                'date' => $datetime,
                'calendar' => $calendar
            ));
            if (!empty($day) && $day->getUser() != $user) {
                $output = array(
                    'success' => true,
                    'id' => $day->getId(),
                    'userId' => $day->getUser()->getId(),
                    'name' => $day->getUser()->getName(),
                    'link' => $day->getGift()->getLink(),
                    'price' => $day->getGift()->getPrice(),
                );
            } else {
                $output = array('success' => false);
            }
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));
            return $response;
        } else {
            throw new Exception('Ajax method');
        }
    }

    public function getEventsAction(Request $request){
        $data = $request->request->all();
        $cookies = $request->cookies->all();
        $dateStart = new DateTime();
        $dateStart->setTimestamp($data['start']);
        $dateEnd = new DateTime();
        $dateEnd->setTimestamp($data['end']);
        if(!empty($cookies)){
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Calendar');
            $calendar = $repository->findOneBy(array('calendarId' => $cookies['cid']));
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:User');
            $user = $repository->findOneBy(array('uid' => $cookies['uid']));
            $er = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Day');
            $days = $er->getEventsByMonth($dateStart, $dateEnd, $calendar, $user);
            $result = array();
            foreach($days as $day){
                $dateFormatted = $day->getDate()->format('Y-m-d');
                $event['title'] = $day->getUser()->getName();
                $event['start'] = $dateFormatted;
                $result[] = $event;
            }
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($result));
            return $response;
        } else {
            throw new Exception('Invalid calendar Id');
        }
    }
} 