<?php

namespace Mundoreader\CalendarBundle\Controller;

use Mundoreader\CalendarBundle\Entity\Calendar;
use Mundoreader\CalendarBundle\Entity\Day;
use Mundoreader\CalendarBundle\Entity\User;
use Mundoreader\CalendarBundle\Form\UserType;
use Mundoreader\CalendarBundle\Form\DayType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $calendarId=null)
    {
        $session = $request->getSession();
        $cookies = $request->cookies->all();
        if($calendarId == null) {
            if(!empty($cookies['cid'])){
                $calendarId = $cookies['cid'];
            } else {
                // create a new calendar id, there should not be another with this id
                $calendarId = md5(time().$this->container->getParameter('secret').'c');
            }
            // go to the new calendar page
            return $this->redirect($this->generateUrl('mundoreader_calendar_homepage', array('calendarId' => $calendarId)));
        } else {
            $response = new Response();
            if(empty($cookies['cid'])){
                $cid = $calendarId;
            } else {
                $cid = $cookies['cid'];
            }
            // we could validate param and cookie :)
            // tell if that calendar id exists
            $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Calendar');
            $calendar = $repository->findOneBy(array('calendarId' => $calendarId));
            if($calendar == null){
                $em = $this->getDoctrine()->getManager();
                $calendar = new Calendar();
                $calendar->setCalendarId($cid);
                $em->persist($calendar);
                $em->flush();
            }
            // tell if this user has cookie with uid
            if(!empty($cookies['uid'])){
                // find user with this uid, user should exist
                $uid = $cookies['uid'];
                $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:User');
                $user = $repository->findOneBy(array('uid' => $uid));
                if(!empty($user)){
                    // save email in session
                    $session->set('userEmail', $user->getEmail());
                }
            } else {
                // create a new uid
                $uid = md5(time().$this->container->getParameter('secret'));
            }
            $formUser = $this->createForm(new UserType(), $user, array(
                'action' => $this->generateUrl('mundoreader_calendar_user_create'),
            ));
            $formDay = $this->createForm(new DayType($calendar), new Day(), array(
                'action' => $this->generateUrl('mundoreader_calendar_day_create'),
            ));
            $response = $this->render('MundoreaderCalendarBundle:Default:index.html.twig', array(
                'calendar' => $calendar,
                'user' => $user,
                'formUser' => $formUser->createView(),
                'formDay' => $formDay->createView(),
            ));
            $response->headers->setCookie(new Cookie("uid", $uid));
            $response->headers->setCookie(new Cookie("cid", $cid));
            return $response;
        }
    }
}
