<?php

namespace App\Event\Subscriber;

use App\DataProvider\GameBehaviorDataProvider;
use App\Entity\Engine\GameObject;
use App\Event\Engine\EngineEvent;
use App\Event\Events;
use App\Model\Map\Movement;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BehaviorsSubscriber implements EventSubscriberInterface
{
    /**
     * @var GameBehaviorDataProvider
     */
    private $dataProvider;

    public static function getSubscribedEvents(): array
    {
        return [
            Events::PRE_MODEL_HANDLE => [
                ['loadBehaviors', -100]
            ],
        ];
    }

    public function __construct(GameBehaviorDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function loadBehaviors(EngineEvent $event): void
    {
        // TODO make generic
        $model = $event->getModel();

        if ($model instanceof Movement) {
            $this->loadObject($model->getMovable());
            $this->loadObject($model->getSource());
            $this->loadObject($model->getDestination());
        }
    }

    private function loadObject(GameObject $gameObject)
    {
        $behaviors = $this->dataProvider->get($gameObject);
        $gameObject->addBehaviors($behaviors);
    }
}
