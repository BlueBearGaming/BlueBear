<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Entity\EntityInstance;
use BlueBear\EngineBundle\Repository\EntityInstanceRepository;
use BlueBear\EngineBundle\Entity\EntityModel;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

class EntityInstanceManager
{
    use ManagerTrait;

    /**
     * Create a instance of an entity model on the map at specific position
     *
     * @param Context $context
     * @param EntityModel $entityModel
     * @param Position $position
     * @param Layer $layer
     * @throws Exception
     */
    public function create(Context $context, EntityModel $entityModel, Position $position, Layer $layer)
    {
        // we try to find if an other of the same instance and same type if on same position
        $entityInstance = $this
            ->getContainer()
            ->get('bluebear.manager.entity_instance')
            ->findByTypeAndPosition($context, $entityModel->getType(), $position);
        /** @BlueBearGameRule : only one entity with the same type with same coordinates */
        if ($entityInstance) {
            throw new Exception('Unable to create entity instance. MapItem "' . $entityInstance->getMapItem()->getId() . '" has already an entity');
        }
        // create a instance from the unit pattern
        $entityInstance = new EntityInstance();
        $entityInstance->hydrateFromModel($entityModel);
        $entityInstance->setLabel('John Panda');
        $entityInstance->setPencil($entityModel->getPencil());

        // we must check if layer is allowed
        $layers = $context->getMap()->getLayers();

        if (!$layer->isAllowed($layers->toArray())) {
            throw new Exception('Request layer to put entity is not allowed');
        }
        // assign it to a map item
        $mapItem = new MapItem();
        $mapItem->setX($position->x);
        $mapItem->setY($position->y);
        $mapItem->setLayer($layer);
        $mapItem->setContext($context);
        $mapItem->setPencil($entityModel->getPencil());
        // unit instance carry relationship
        $entityInstance->setMapItem($mapItem);
        // saving entity
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($mapItem);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    /**
     * Return a entity instance by its position and its context
     *
     * @param Context $context
     * @param string $instanceType
     * @param Position $position
     * @return EntityInstance
     * @throws NonUniqueResultException
     */
    public function findByTypeAndPosition(Context $context, $instanceType, Position $position)
    {
        return $this
            ->getRepository()
            ->findByTypeAndPosition($context->getId(), $position->x, $position->y, $instanceType)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Return current manager repository
     *
     * @return EntityInstanceRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearEngineBundle:EntityInstance');
    }
}
