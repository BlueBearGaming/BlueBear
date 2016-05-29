<?php

namespace BlueBear\BaseBundle\Twig;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use Symfony\Component\HttpFoundation\Request;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * UtilsExtension
 *
 * Twig utils functions
 */
class UtilsExtension extends Twig_Extension
{
    use ContainerTrait;

    /**
     * Return true if $route is the current route
     *
     * @param $route
     * @return bool
     */
    public function isCurrentRoute($route)
    {
        /** @var Request $request */
        $request = $this->getContainer()->get('request');

        return $route == $request->get('_route');
    }

    /**
     * Return $cssClass ("active" by default) if $route is current route
     *
     * @param $route
     * @param string $cssClass
     * @return string
     */
    public function getClassForRoute($route, $cssClass = 'active')
    {
        return ($this->isCurrentRoute($route)) ? $cssClass : '';
    }

    /**
     * Return $cssClass ("active" by default) if one route in $routes is current route
     *
     * @param array $routes
     * @param string $cssClass
     * @return string
     */
    public function getClassForRoutes(array $routes, $cssClass = 'active')
    {
        $classes = [];

        foreach ($routes as $route) {
            $classes[] = $this->getClassForRoute($route, $cssClass);
        }
        $classes = array_unique($classes);

        return implode(' ', $classes);
    }

    /**
     * Append a named value in request query string
     *
     * @param Request $request
     * @param $name
     * @param $value
     * @return string
     */
    public function appendToQueryString(Request $request, $name, $value)
    {
        $queryString = '?';
        $query = $request->query->all();
        $query[$name] = $value;
        $parametersCount = count($query);
        $count = 1;

        foreach ($query as $parameterName => $parameterValue) {
            $queryString .= $parameterName . '=' . $parameterValue;

            if ($count != $parametersCount) {
                $queryString .= '&';
            }
            $count++;
        }
        return $queryString;
    }

    public function createArrayKey($key, $value = null, array $array = [])
    {
        $array[$key] = $value;

        return $array;
    }

    /**
     * Return twig methods
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('isCurrentRoute', [$this, 'isCurrentRoute']),
            new Twig_SimpleFunction('getClassForRoute', [$this, 'getClassForRoute']),
            new Twig_SimpleFunction('getClassForRoutes', [$this, 'getClassForRoutes']),
            new Twig_SimpleFunction('createArrayKey', [$this, 'createArrayKey']),
            new Twig_SimpleFunction('appendToQueryString', [$this, 'appendToQueryString'])
        ];
    }

    public function getName()
    {
        return 'bluebear_base_extension';
    }
}
