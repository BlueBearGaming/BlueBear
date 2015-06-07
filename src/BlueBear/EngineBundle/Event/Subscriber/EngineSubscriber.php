<?php

namespace BlueBear\EngineBundle\Event\Subscriber;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Behavior\HasEventDispatcher;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Manager\ContextManager;
use BlueBear\EngineBundle\Engine\Annotation\AnnotationProcessor;
use BlueBear\EngineBundle\Engine\Entity\Race;
use BlueBear\EngineBundle\Engine\UnitOfWork\EntityReference;
use BlueBear\EngineBundle\Event\EngineEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Yaml\Parser;

class EngineSubscriber implements EventSubscriberInterface
{
    use HasEventDispatcher, ContainerTrait;

    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * @var AnnotationProcessor
     */
    protected $annotationProcessor;

    /**
     * Subscribes on engine event
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EngineEvent::ENGINE_ON_ENGINE_EVENT => 'onEngineEvent',
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    /**
     * On each engine event, we should load the map
     *
     * @param EngineEvent $event
     * @return EngineEvent
     * @throws Exception
     */
    public function onEngineEvent(EngineEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->contextId) {
            throw new Exception('Invalid context id');
        }
        /** @var Context $context */
        $context = $this
            ->getContextManager()
            ->find($request->contextId);

        if (!$context) {
            throw new Exception('Context not found or invalid context map');
        }
        $event->setContext($context);
    }

    public function onKernelRequest(KernelEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::MASTER_REQUEST) {
            $parse = new Parser();
            $finder = new Finder();
            $finder->files()->in(__DIR__ . '/../../Resources/data');

            /** @var SplFileInfo $file */
            foreach ($finder as $file) {
                $data = $parse->parse(file_get_contents($file->getRealPath()));

                foreach ($data as $entityData) {
                    $this->annotationProcessor->process($entityData);
                }
            }
            $this->annotationProcessor->processRelations();
            $unitOfWork = $this->getContainer()->get('bluebear.engine.unit_of_work');

            /** @var Race $dwarf */
            $dwarf = $unitOfWork->load(new EntityReference('BlueBear\EngineBundle\Engine\Entity\Race', 'dwarf'));
            $test = $dwarf->getClassSize()->get(0);

            var_dump($test);


            die('panda');
        }
    }

    /**
     * @return ContextManager
     */
    public function getContextManager()
    {
        return $this->contextManager;
    }

    /**
     * @param ContextManager $contextManager
     */
    public function setContextManager($contextManager)
    {
        $this->contextManager = $contextManager;
    }

    /**
     * @return AnnotationProcessor
     */
    public function getAnnotationProcessor()
    {
        return $this->annotationProcessor;
    }

    /**
     * @param AnnotationProcessor $annotationProcessor
     */
    public function setAnnotationProcessor($annotationProcessor)
    {
        $this->annotationProcessor = $annotationProcessor;
    }
}
