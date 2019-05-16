<?php

namespace App\Engine;

use App\Contracts\Factory\ModelFactoryInterface;
use App\Contracts\Handler\ModelHandlerInterface;
use App\Contracts\Model\ModelInterface;
use App\Engine\Exception\EngineException;
use App\Engine\Request\EngineRequest;
use App\Engine\Response\EngineResponse;
use App\Event\Engine\EngineEvent;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\SerializerInterface;

class Engine implements EngineInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var ModelFactoryInterface
     */
    private $modelFactory;
    /**
     * @var ModelHandlerInterface
     */
    private $modelHandler;

    public function __construct(
        SerializerInterface $serializer,
        EventDispatcherInterface $eventDispatcher,
        ModelFactoryInterface $modelFactory,
        ModelHandlerInterface $modelHandler
    ) {
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
        $this->modelFactory = $modelFactory;
        $this->modelHandler = $modelHandler;
    }

    public function run(Request $request): Response
    {
        $engineRequest = $this->createEngineRequest($request);
        $data = $this->resolveData($engineRequest->getModelName(), $engineRequest->getData());

        $model = $this->modelFactory->create($engineRequest->getModelName(), $data);
        $event = new EngineEvent($model);

        $this->eventDispatcher->dispatch(Events::PRE_MODEL_HANDLE, $event);
        $this->modelHandler->handle($model);
        $this->eventDispatcher->dispatch(Events::POST_MODEL_HANDLE, $event);

        return $this->createEngineResponse($model);
    }

    private function createEngineRequest(Request $request): EngineRequest
    {
        if ('' === $request->getContent()) {
            throw new EngineException('Invalid data provided', 400);
        }
        $engineRequest = $this->serializer->deserialize($request->getContent(), EngineRequest::class, 'json');

        if (!$engineRequest instanceof EngineRequest) {
            throw new EngineException('The data can not be deserialized into a engine request object');
        }

        if ('' === $engineRequest->getModelName()) {
            throw new EngineException('The model name cannot be empty');
        }

        return $engineRequest;
    }

    private function createEngineResponse(ModelInterface $model): EngineResponse
    {
        $data = $this->serializer->serialize($model, 'json');

        return new EngineResponse($data, 200, [], true);
    }

    private function resolveData(string $modelName, array $data): array
    {
        $resolver = new OptionsResolver();
        $this->modelFactory->configure($modelName, $resolver);

        return $resolver->resolve($data);
    }
}
