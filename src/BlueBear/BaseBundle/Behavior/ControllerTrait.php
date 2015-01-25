<?php

namespace BlueBear\BaseBundle\Behavior;

use Exception;
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
 */
trait ControllerTrait
{
    use ContainerTrait;

    /**
     * Abstract create not found exception method. Should return a NotFoundException
     *
     * @param string $message
     * @param Exception $previous
     * @return NotFoundHttpException
     */
    public abstract function createNotFoundException($message = 'Not Found', Exception $previous = NULL);

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
     * Abstract get method. Should return a container interface
     *
     * @param $id
     * @return mixed
     */
    public abstract function get($id);

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
     * @param int $status
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        if (substr($url, 0, 1) == '@') {
            $route = substr($url, 1);
            $url = $this->generateUrl($route);
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
        return $this->get('router');
    }

    /**
     * Return current translator
     *
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * Return current session
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->get('session');
    }

    /**
     * Return event dispatcher
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Translate a string
     *
     * @param $string
     * @param array $parameters
     * @return string
     */
    protected function translate($string, $parameters = array())
    {
        return $this->getTranslator()->trans($string, $parameters);
    }
} 