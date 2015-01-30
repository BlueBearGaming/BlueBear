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

        foreach ($menuConfigs as $menuName => $menuConfig) {
            $menu = new Menu();
            $menu->setName($menuName);
            $menu->setTemplate($menuConfig['template']);
            //, $menuConfig['template'], $mainItem
            $mainItemConfig = array_key_exists('main_item', $menuConfig) ? $menuConfig['main_item'] : '';

            if ($mainItemConfig) {
                $mainItem = new MenuItem();
                $mainItem->setTitle($mainItemConfig['title']);
                $mainItem->setRoute($mainItemConfig['route']);
                $menu->setMainItem($mainItem);
            }
            //$menuName, $menuConfig['template'], $mainItem

            foreach ($menuConfig['items'] as $item) {
                // TODO handle custom menus, not related to an admin
                // check if an admin with correct name exists
                if (!array_key_exists('admin', $item)) {
                    throw new Exception('Invalid menu name. It should be an admin name');
                }
                /** @var Admin $admin */
                $admin = $container->get('bluebear.admin.factory')->getAdmin($item['admin']);
                // check if action is granted for admin
                if (!$admin->isActionGranted($item['action'], $item['permissions'])) {
                    throw new Exception('Action ' . $item['action'] . ' is not allowed for admin ' . $admin->getName());
                }
                $action = $admin->getAction($item['action']);

                $menuItem = new MenuItem();
                $menuItem->setTitle($item['title']);
                $menuItem->setRoute($action->getRoute());
                $menuItem->setParameters($action->getParameters());
                $menu->addItem($menuItem);
            }
            $this->menus[$menu->getName()] = $menu;
            //var_dump($this->menus);
            //die;
        }
    }

    /**
     * @return Menu[]
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param $name
     * @return Menu
     * @throws Exception
     */
    public function getMenu($name)
    {
        if (!array_key_exists($name, $this->menus)) {
            throw new Exception('Invalid menu name');
        }
        return $this->menus[$name];
    }
}