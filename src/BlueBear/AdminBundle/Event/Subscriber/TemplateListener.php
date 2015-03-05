<?php

namespace BlueBear\AdminBundle\Event\Subscriber;

use BlueBear\AdminBundle\Admin\Application\ApplicationConfiguration;
use BlueBear\BaseBundle\Behavior\ContainerTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TemplateListener implements EventSubscriberInterface
{
    use ContainerTrait;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    /**
     * @param KernelEvent $event
     * @return array|null
     */
    public function onKernelRequest(KernelEvent $event)
    {
        $request = $event->getRequest();
        $twig = $this->container->get('twig');
        $applicationConfigArray = $this->container->getParameter('bluebear.admin.application');

        // creating application configuration object
        $applicationConfig = new ApplicationConfiguration();
        $applicationConfig->lang = $request->getLocale();
        $applicationConfig->layout = $this->container->getParameter('bluebear.admin.layout');
        $applicationConfig->title = $applicationConfigArray['title'];

        $twig->addGlobal('config', $applicationConfig);
    }
}