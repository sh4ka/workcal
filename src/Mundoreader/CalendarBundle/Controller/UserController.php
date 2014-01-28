<?php
/**
 * Created by PhpStorm.
 * User: jesus
 * Date: 23/01/14
 * Time: 16:58
 */

namespace Mundoreader\CalendarBundle\Controller;

use Mundoreader\CalendarBundle\Entity\User;
use Mundoreader\CalendarBundle\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

    public function createAction(Request $request){
        // if we do not have uid cookie, do nothing
        $cookies = $request->cookies->all();
        if(empty($cookies)){
            throw new Exception('You shall not pass!');
        } else {
            $uid = $cookies['uid'];
            $cid = $cookies['cid'];
            $form = $this->createForm(new UserType(), new User());
            $form->handleRequest($request);
            if ($form->isValid()) {
                //  find user with this email
                $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:User');
                $user = $repository->findOneBy(array('email' => $form->getData()->getEmail()));
                $repository = $this->getDoctrine()->getRepository('MundoreaderCalendarBundle:Calendar');
                $calendar = $repository->findOneBy(array('calendarId' => $cid));
                $em = $this->getDoctrine()->getManager();
                if(!empty($user)){
                    // user exists, update
                    $user->setUid($uid);
                    $user->setCalendar($calendar);
                } else {
                    // new user, create
                    $user = $form->getData();
                    $user->setUid($uid);
                    $user->setCalendar($calendar);
                    $em->persist($user);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('mundoreader_calendar_homepage', array('calendarId' => $cid)));
            } else {
                throw new Exception('Invalid form');
            }
        }
    }

    public function find(){
        $request = $this->container->get('request');
        $data1 = $request->query->get('data1');
        $data2 = $request->query->get('data2');
        $response = array("code" => 100, "success" => true);
        return new Response(json_encode($response));
    }
} 