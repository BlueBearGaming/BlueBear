<?php

namespace BlueBear\GameBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Manager\MapItemManager;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Entity\UnitInstance;
use BlueBear\GameBundle\Game\EntityType;
use BlueBear\GameBundle\Game\EntityTypeAttribute;

/**
 * UnitFactory
 *
 * @toRemove()
 */
class UnitFactory
{
    use ContainerTrait;

    /**
     * @var EntityTypeAttribute[]
     */
    protected $entityTypeAttributes = [];

    /**
     * @var EntityType[]
     */
    protected $entityTypes = [];

    /**
     * @var EntityModel[]
     */
    protected $unitsModels = [];

    /**
     * @return UnitManager
     */
    protected function getUnitManager()
    {
        return $this->getContainer()->get('bluebear.manager.unit');
    }

    /**
     * @return \BlueBear\GameBundle\Game\EntityType[]
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @param \BlueBear\GameBundle\Game\EntityType[] $entityType
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @return MapItemManager
     */
    protected function getMapItemManager()
    {
        return $this->getContainer()->get('bluebear.manager.map_item');
    }
}