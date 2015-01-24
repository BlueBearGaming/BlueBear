<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    use ControllerTrait;

    protected $templateName;

    public function menuAction(Request $request)
    {
        $admins = $this->get('bluebear.admin.factory');
        $this->loadMenuConfiguration();




        return new Response('lol');
    }

    protected function loadMenuConfiguration()
    {
        $menuConfig = $this->getContainer()->getParameter('bluebear.menus');

        //var_dump($menuConfig);
        //die;
    }
}