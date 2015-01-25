<?php

namespace BlueBear\AdminBundle\Menu;

use BlueBear\AdminBundle\Admin\Admin;
use BlueBear\AdminBundle\Routing\RoutingLoader;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuFactory
{
    protected $menus = [];

    public function __construct(ContainerInterface $container, RoutingLoader $routingLoader)
    {
        $menuConfigs = $container->getParameter('bluebear.menus');
        $admins = $container->get('bluebear.admin.factory')->getAdmins();

        foreach ($menuConfigs as $menuName => $menuConfig) {
            // check if an admin with correct name exists
            if (!array_key_exists($menuName, $admins)) {
                throw new Exception('Invalid menu name. It should be an admin name');
            }
            /** @var Admin $admin */
            $admin = $admins[$menuName];
            $menu = new Menu($menuName);

            foreach ($menuConfig['items'] as $item) {
                $actions = $item['actions'];

                foreach ($actions as $action) {
                    // check if action is granted for admin
                    if (!array_key_exists($action, $admin->getActions())) {
                        throw new Exception('Action ' . $action . ' is not allowed for admin ' . $admin->getName());
                    }
                    $route = $routingLoader->generateRouteForAction($admin, $action);
                    $menuItem = new MenuItem($route);
                    $menu->addItem($menuItem);
                }
            }
            $this->menus[] = $menu;
        }
    }
}