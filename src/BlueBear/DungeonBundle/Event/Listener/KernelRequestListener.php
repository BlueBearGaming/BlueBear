<?php

namespace BlueBear\DungeonBundle\Event\Listener;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\DungeonBundle\Annotation\AnnotationProcessor;
use BlueBear\DungeonBundle\Entity\Race\Race;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use Exception;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Yaml\Parser;

class KernelRequestListener
{
    use ContainerTrait;
    /**
     * @var AnnotationProcessor
     */
    protected $annotationProcessor;

    protected $isUnitOfWorkLoaded = false;

    public function onKernelRequest(KernelEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::MASTER_REQUEST && !$this->isUnitOfWorkLoaded) {
            $parse = new Parser();
            $files = [];
            $kernel = $this->container->get('kernel');
            $configuration = $this->container->getParameter('bluebear.game.data');

            foreach ($configuration as $file) {
                $file = $kernel->locateResource($file);

                if (!$file) {
                    throw new Exception('Invalid data file ' . $file);
                }
                $files[] = $file;
            }
            foreach ($files as $file) {
                $data = $parse->parse(file_get_contents($file));

                foreach ($data as $entityData) {
                    $this->annotationProcessor->process($entityData);
                }
            }
            $this->annotationProcessor->processRelations();
            $this->isUnitOfWorkLoaded = true;
            /** @var Race $dwarf */
            $dwarf = $this->getContainer()->get('bluebear.engine.unit_of_work')->load(new EntityReference(
                Race::class,
                'dwarf'
            ));
            // normal loading
            //var_dump($dwarf);
            //var_dump($dwarf->getAttributeModifiers());
            // lazy loading
            //var_dump($dwarf->getAttributeModifiers()->get(0));
            //var_dump($dwarf->getAttributeModifiers());
            //var_dump($dwarf->getRacialTraits());
            //print_r($this->getContainer()->get('bluebear.engine.unit_of_work'));
        }
    }

    /**
     * @param AnnotationProcessor $annotationProcessor
     */
    public function setAnnotationProcessor($annotationProcessor)
    {
        $this->annotationProcessor = $annotationProcessor;
    }

}
