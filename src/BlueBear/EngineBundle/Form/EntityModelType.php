<?php

namespace BlueBear\EngineBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\EngineBundle\Engine\Entity\EntityType;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Factory\EntityTypeFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityModelType extends AbstractType
{
    /**
     * @var EntityTypeFactory
     */
    protected $entityTypeFactory;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityModel $entityModel */
        $entityModel = $options['data'];

        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('label', 'text');
        $builder->add('pencil');
        // type cannot by changed after creation, attributes are available only after the type is chosen
        if ($entityModel->getId()) {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes(),
                'read_only' => true,
                'disabled' => 'disabled',
            ]);
            $builder->add('behaviors', 'choice', [
                'choices' => $this->getSortedEntityBehaviors(),
                'read_only' => true,
                'disabled' => 'disabled',
                'multiple' => true,
                'expanded' => true,
            ]);
        } else {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes(),
            ]);
        }
        $builder->add('allowedLayerTypes', 'choice', [
            'choices' => Constant::getLayerTypes(),
            'multiple' => true,
            'expanded' => true,
            'horizontal_input_wrapper_class' => 'col-sm-9 form-inline-checkboxes',
        ]);
        if ($entityModel->getId()) {
            $builder->add('attributes', 'attribute_collection');
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\EngineBundle\Entity\EntityModel',
            'entity_types' => []
        ]);
    }

    public function getName()
    {
        return 'entity_model';
    }

    public function setEntityTypeFactory(EntityTypeFactory $entityTypeFactory)
    {
        $this->entityTypeFactory = $entityTypeFactory;
    }

    protected function getSortedEntityTypes()
    {
        $sorted = [];
        $entityTypes = $this->entityTypeFactory->getEntityTypes();

        /** @var EntityType $entityType */
        foreach ($entityTypes as $entityType) {
            $sorted[$entityType->getName()] = $entityType;
        }
        return $entityTypes;
    }

    protected function getSortedEntityBehaviors()
    {
        $sorted = [];
        $behaviors = $this->entityTypeFactory->getEntityBehaviors();

        foreach ($behaviors as $behavior) {
            $sorted[$behavior->getName()] = ucfirst($behavior->getName());
        }
        return $sorted;
    }
}
