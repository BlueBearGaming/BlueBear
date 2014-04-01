<?php


namespace BlueBear\BackofficeBundle\Controller\Behavior;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

trait ControllerBehavior
{
    // Symfony controllers methods
    abstract function createNotFoundException($message = 'Not Found', \Exception $previous = NULL);
    abstract function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);
    abstract function get($id);


    /**
     * Throw a 404 Exception if $boolean is false or null
     *
     * @param $boolean
     * @param string $message
     */
    public function redirect404Unless($boolean, $message = 'Error 404')
    {
        if (!$boolean) {
            throw $this->createNotFoundException($message);
        }
    }

    /**
     * Redirects response to an url or a route
     *
     * @param string $url
     * @param int $status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        if (substr($url, 0, 1) == '@') {
            $route = substr($url, 1);
            $url = $this->generateUrl($route);
        }
        return parent::redirect($url, $status);
    }

    /**
     * Set a flash notice in session for next request. The message is translated
     *
     * @param $message
     * @param array $parameters
     * @internal param bool $useTranslation
     * @internal param array $translationParameters
     * @return void
     */
    protected function setMessage($message, $parameters = array())
    {
        $this->getSession()->getFlashBag()->add('notice', $this->translate($message, $parameters));
    }

    /**
     * Return a configuration value
     *
     * @param $key
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->container->getParameter($key);
    }

    public function getRouting()
    {
        return $this->get('router');
    }

    /**
     * Return current translator
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * Return current session
     * @return Session
     */
    protected function getSession()
    {
        return $this->get('session');
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