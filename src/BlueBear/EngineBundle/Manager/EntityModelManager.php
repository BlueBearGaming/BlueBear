<?php

namespace BlueBear\EngineBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerTrait;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Entity\EntityModelAttribute;
use BlueBear\EngineBundle\Engine\Entity\EntityBehavior;
use BlueBear\EngineBundle\Engine\Entity\EntityTypeAttribute;

class EntityModelManager
{
    use ManagerTrait;

    /**
     * Save entity model. On entity model creation, create behaviors and attributes from configuration
     *
     * @param EntityModel $entityModel
     */
    public function save(EntityModel $entityModel)
    {
        // on entity model creation, we should create default model attribute from entity type in creation
        if (!$entityModel->getId()) {
            $entityType = $this
                ->getContainer()
                ->get('bluebear.game.entity_type_factory')
                ->getEntityType($entityModel->getType());
            $entityTypeAttributes = $entityType->getAttributes();
            $entityTypeBehaviors = $entityType->getBehaviors();
            $existingAttributes = $entityModel->getAttributes();
            $sortedExistingAttributes = [];
            /** @var EntityModelAttribute $existingAttribute */
            foreach ($existingAttributes as $existingAttribute) {
                $sortedExistingAttributes[$existingAttribute->getName()] = $existingAttribute;
            }
            /** @var EntityTypeAttribute $attribute */
            foreach ($entityTypeAttributes as $attribute) {
                if (!array_key_exists($attribute->getName(), $sortedExistingAttributes)) {
                    $entityModelAttribute = new EntityModelAttribute();
                    $entityModelAttribute->setName($attribute->getName());
                    $entityModelAttribute->setLabel($attribute->getLabel());
                    $entityModelAttribute->setType($attribute->getType());
                    // those attributes are set by default for this model, they can not be removed
                    $entityModelAttribute->setIsDefault(true);
                    $entityModel->addAttribute($entityModelAttribute);
                }
            }
            /** @var EntityBehavior $behavior */
            foreach ($entityTypeBehaviors as $behavior) {
                $entityModel->addBehavior($behavior->getName());
            }
        }
        $this->getEntityManager()->persist($entityModel);
        $this->getEntityManager()->flush();
    }

    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearEngineBundle:EntityModel');
    }
}
