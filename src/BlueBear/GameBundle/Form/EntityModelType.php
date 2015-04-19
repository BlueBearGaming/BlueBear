<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Factory\EntityTypeFactory;
use BlueBear\GameBundle\Game\EntityType;
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
        $builder->add('pencil');
        // type cannot by changed after creation, attributes are available only after the type is chosen
        if ($entityModel->getId()) {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes(),
                'attr' => [
                    'disabled' => 'disabled'
                ]
            ]);
            $builder->add('behaviors', 'choice', [
                'choices' => $this->getSortedEntityBehaviors(),
                'read_only' => true,
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
            $builder->add('attributes', 'attribute_collection', [
                'type' => 'entity_model_attribute',
                'allow_add' => true,
                'widget_add_btn' => [
                    'label' => 'Add attribute'
                ],
                'options' => [
                    'label' => false,
                ],
            ]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\GameBundle\Entity\EntityModel',
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