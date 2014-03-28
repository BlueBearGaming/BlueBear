<?php

namespace BlueBear\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function mainAction()
    {


        return $this->render('BlueBearBackofficeBundle:Main:index.html.twig');
    }
}
