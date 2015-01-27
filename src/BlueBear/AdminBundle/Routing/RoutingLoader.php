<?php

namespace BlueBear\AdminBundle\Routing;

use BlueBear\AdminBundle\Admin\Action;
use BlueBear\AdminBundle\Admin\Admin;
use BlueBear\BaseBundle\Behavior\ContainerTrait;
use RuntimeException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * RoutingLoader
 *
 * Creates routing for configured entities
 */
class RoutingLoader implements LoaderInterface
{
    use ContainerTrait;

    protected $loaded = false;

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new RuntimeException('Do not add the "extra" loader twice');
        }
        $routes = new RouteCollection();
        //$admins = $this->getContainer()->getParameter('bluebear.admins');
        $admins = $this->getContainer()->get('bluebear.admin.factory')->getAdmins();
        // creating a route by admin and action
        /** @var Admin $admin */
        foreach ($admins as $admin) {
            $requirements = [];
            $entityPath = $admin->getEntityPath();
            $actions = $admin->getActions();
            // by default, actions are create, edit, delete, list
            /** @var Action $action */
            foreach ($actions as $action) {
                $path = '/' . $entityPath . '/' . $action->getName();
                $defaults = [
                    '_controller' => $admin->getController() . ':' . $action->getName(),
                ];
                if (in_array($action, ['delete', 'edit'])) {
                    $path .= '/{id}';
                }
                // adding route to collection
                $routes->add($this->generateRouteForAction($admin, $action),
                    new Route($path, $defaults, $requirements));
            }
        }
        $this->loaded = true;

        return $routes;
    }

    public function generateRouteForAction(Admin $admin, Action $action)
    {
        $path = 'bluebear_admin_' . strtolower($admin->getName()) . '_' . $action->getName();

        if (in_array($action, ['delete', 'list'])) {
            // TODO add parameters id ?
        }
        return $path;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }

    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    public function setResolver(LoaderResolverInterface $resolver)
    {
        // same as above
    }
}