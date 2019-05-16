<?php

namespace App\Event\Subscriber;

use App\Event\Engine\EngineEvent;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ValidationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::PRE_MODEL_HANDLE => [
                ['validate', 0]
            ],
        ];
    }

    public function validate(EngineEvent $event): void
    {

    }
}
