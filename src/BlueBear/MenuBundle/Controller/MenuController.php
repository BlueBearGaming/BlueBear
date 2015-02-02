<?php

namespace BlueBear\MenuBundle\Controller;

use BlueBear\AdminBundle\Admin\AdminFactory;
use BlueBear\MenuBundle\Menu\Menu;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    use ControllerTrait;

    protected $templateName;

    public function menuAction(Request $request)
    {
        $masterRequest = $this->getContainer()->get('request_stack')->getMasterRequest();
        /** @var Menu $menu */
        $menu = $this->get('bluebear.menu.factory')->getMenu($request->get('name'));

        return $this->render($menu->getTemplate(), [
            'menu' => $menu,
            'currentRoute' => $masterRequest->get('_route')
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