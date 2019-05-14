<?php

namespace App\Event\Engine;

use App\Contracts\Model\ModelInterface;
use Symfony\Component\EventDispatcher\Event;

class EngineEvent extends Event
{
    /**
     * @var ModelInterface
     */
    private $model;

    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }
}
