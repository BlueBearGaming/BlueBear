<?php

namespace BlueBear\FireBundle\Event\Subscriber;

use LAG\AdminBundle\Event\AdminEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EAVAdminConfigurationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AdminEvent::ADMIN_CREATE => 'adminCreate'
        ];
    }

    public function adminCreate(AdminEvent $event)
    {
        if ($event->getAdminName() != 'fire') {
            return;
        }
        var_dump($event);
        die;
    }
}
