<?php


namespace Tissot\CctpExpert\Bundle\FrontofficeBundle\Controller\Behavior;


trait ControllerBehavior
{
    abstract function createNotFoundException($message = 'Not Found', \Exception $previous = NULL);
    /**
     * Redirige en page 404 à moins que $boolean ne soit true
     * @param $boolean
     * @param string $message
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function redirect404Unless($boolean, $message = 'Erreur 404')
    {
        if (!$boolean) {
            throw $this->createNotFoundException($message);
        }
    }
} 