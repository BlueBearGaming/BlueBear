<?php

namespace BlueBear\CoreBundle\Twig;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use Symfony\Component\HttpFoundation\Request;
use Twig_Extension;
use Twig_SimpleFunction;

// TODO move in base bundle
class UtilsExtension extends Twig_Extension
{
    use ContainerTrait;

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('isCurrentRoute', [$this, 'isCurrentRoute']),
            new Twig_SimpleFunction('getClassForRoute', [$this, 'getClassForRoute'])
        ];
    }

    public function isCurrentRoute($route)
    {
        /** @var Request $request */
        $request = $this->getContainer()->get('request');

        return $route == $request->get('_route');
    }

    public function getClassForRoute($route, $cssClass = 'active')
    {
        return ($this->isCurrentRoute($route)) ? $cssClass : '';
    }

    public function getName()
    {
        return 'bluebear_extension';
    }
}