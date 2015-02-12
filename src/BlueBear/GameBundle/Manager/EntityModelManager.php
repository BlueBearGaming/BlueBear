<?php

namespace BlueBear\GameBundle\Manager;

use BlueBear\BaseBundle\Behavior\ManagerBehavior;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Entity\EntityModelAttribute;
use BlueBear\GameBundle\Game\EntityTypeAttribute;

class EntityModelManager
{
    use ManagerBehavior;

    /**
     * @param EntityModel $entityModel
     */
    public function save(EntityModel $entityModel)
    {
        // on entity model creation, we should create default model attribute from entity type in creation
        if (!$entityModel->getId()) {
            $entityType = $this->getContainer()->get('bluebear.game.entity_factory')->getEntityType($entityModel->getType());
            $entityTypeAttributes = $entityType->getAttributes();

            /** @var EntityTypeAttribute $attribute */
            foreach ($entityTypeAttributes as $attribute) {
                $entityModelAttribute = new EntityModelAttribute();
                $entityModelAttribute->setName($attribute->getName());
                $entityModelAttribute->setLabel($attribute->getLabel());
                $entityModelAttribute->setType($attribute->getType());
                // those attributes are set by default for this model, they can not be changed
                $entityModelAttribute->setIsDefault(true);
                $entityModel->addAttributes($entityModelAttribute);
            }
        }
        $this->getEntityManager()->persist($entityModel);
        $this->getEntityManager()->flush();
    }

    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBearGameBundle:EntityModel');
    }
}