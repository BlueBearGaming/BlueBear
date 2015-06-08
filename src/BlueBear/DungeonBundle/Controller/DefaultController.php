<?php

namespace BlueBear\DungeonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BlueBearDungeonBundle:Default:index.html.twig', array('name' => $name));
    }
}
