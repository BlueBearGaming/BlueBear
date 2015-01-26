<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\AdminBundle\Admin\AdminFactory;
use BlueBear\AdminBundle\Menu\Menu;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    use ControllerTrait;

    protected $templateName;

    public function menuAction(Request $request)
    {
        /** @var Menu $menu */
        $menu = $this->get('bluebear.menu.factory')->getMenu($request->get('name'));

        return $this->render($menu->getTemplate(), [
            'menu' => $menu
        ]);
    }



    /**
     * @return AdminFactory
     */
    protected function getAdminFactory()
    {
        return $this->get('bluebear.admin.factory');
    }
}