<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\AdminBundle\Admin\AdminFactory;
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



    /**
     * @return AdminFactory
     */
    protected function getAdminFactory()
    {
        return $this->get('bluebear.admin.factory');
    }
}