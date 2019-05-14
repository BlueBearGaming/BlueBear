<?php

namespace App\Event\Subscriber;

use App\Event\Engine\EngineEvent;
use App\Event\Events;
use App\Model\Map\Movement;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PositionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::PRE_MODEL_HANDLE => 'preModelHandle',
        ];
    }

    public function __construct()
    {
    }

    public function preModelHandle(EngineEvent $event): void
    {
        $model = $event->getModel();

        if (!$model instanceof Movement) {
            return;
        }

    }
}
