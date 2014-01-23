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
                $user = $form->getData();
                $user->setUid($uid);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('mundoreader_calendar_homepage', array('calendarId' => $cid)));
            } else {
                throw new Exception('Invalid form');
            }
        }
    }
} 