<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    use ControllerBehavior;

    public function homepageAction()
    {
        return $this->render('BlueBearBackofficeBundle:Main:homepage.html.twig');
    }
}
