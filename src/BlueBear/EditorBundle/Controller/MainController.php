<?php

namespace BlueBear\EditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {


        return $this->render('BlueBearEditorBundle:Main:index.html.twig');
    }
}
