<?php

namespace BlueBear\BaseBundle\Behavior;

use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * ControllerTrait
 *
 * Helper trait for Symfony controllers
 *
 * @property ContainerInterface $container
 */
trait ControllerTrait
{
    /**
     * Abstract create not found exception method. Should return a NotFoundException
     *
     * @param string $message
     * @param Exception $previous
     * @return NotFoundHttpException
     */
    public abstract function createNotFoundException($message = 'Not Found', Exception $previous = null);

    /**
     * Abstract generate url method. Should return a url string
     *
     * @param $route
     * @param array $parameters
     * @param bool $referenceType
     * @return string
     */
    public abstract function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);

    /**
     * Throw a 404 Exception if $boolean is false or null
     *
     * @param $boolean
     * @param string $message
     */
    public function forward404Unless($boolean, $message = '404 Not Found')
    {
        if (!$boolean) {
            throw $this->createNotFoundException($message);
        }
    }

    /**
     * Redirect response to an url or a route
     *
     * @param string $url
     * @param array $parameters
     * @param int $status
     * @return RedirectResponse
     */
    public function redirect($url, $parameters = [], $status = 302)
    {
        if (substr($url, 0, 1) == '@') {
            $route = substr($url, 1);
            $url = $this->generateUrl($route, $parameters);
        }
        return new RedirectResponse($url, $status);
    }

    /**
     * Set a flash notice in session for next request. The message is translated
     *
     * @param $message
     * @param string $type
     * @param array $parameters
     */
    public function setMessage($message, $type = 'info', $parameters = [])
    {
        $this->getSession()->getFlashBag()->add($type, $this->translate($message, $parameters));
    }

    /**
     * Return a configuration value
     *
     * @param $key
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->getContainer()->getParameter($key);
    }

    /**
     * Return routing
     *
     * @return RouterInterface
     */
    public function getRouting()
    {
        return $this->getContainer()->get('router');
    }

    /**
     * Return current translator
     *
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->getContainer()->get('translator');
    }

    /**
     * Return current session
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->getContainer()->get('session');
    }

    /**
     * Return event dispatcher
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * Translate a string
     *
     * @param $string
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    protected function translate($string, $parameters = [], $domain = null, $locale = null)
    {
        return $this->getTranslator()->trans($string, $parameters, $domain, $locale);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
