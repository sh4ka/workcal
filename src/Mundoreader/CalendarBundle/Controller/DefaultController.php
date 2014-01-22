<?php

namespace Mundoreader\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($calendarId=null)
    {
        if($calendarId == null) {
            // create a new calendar id
            $calendarId = uniqid(true);
        } else {
            // tell if that calendar id exists
        }
        return $this->render('MundoreaderCalendarBundle:Default:index.html.twig');
    }
}
