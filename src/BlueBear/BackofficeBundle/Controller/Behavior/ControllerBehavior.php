<?php


namespace BlueBear\BackofficeBundle\Controller\Behavior;


trait ControllerBehavior
{
    abstract function createNotFoundException($message = 'Not Found', \Exception $previous = NULL);

    public function redirect404Unless($boolean, $message = 'Erreur 404')
    {
        if (!$boolean) {
            throw $this->createNotFoundException($message);
        }
    }
} 