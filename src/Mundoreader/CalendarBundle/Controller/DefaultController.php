<?php

namespace Mundoreader\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MundoreaderCalendarBundle:Default:index.html.twig', array('name' => $name));
    }
}
