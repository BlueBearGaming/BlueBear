<?php

namespace BlueBear\EditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BlueBearEditorBundle:Default:index.html.twig', array('name' => $name));
    }
}
