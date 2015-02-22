<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Factory\EntityFactory;
use BlueBear\GameBundle\Game\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityModelType extends AbstractType
{
    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityModel $entityModel */
        $entityModel = $options['data'];

        $builder->add('name', 'text', [
            'help_block' => 'Name of the unit'
        ]);
        $builder->add('pencil');

        $builder->add('allowedLayerTypes', 'choice', [
            'choices' => Constant::getLayerTypes(),
            'multiple' => true,
            'expanded' => true,
            'horizontal_input_wrapper_class' => 'col-sm-9 form-inline-checkboxes',
        ]);
        if ($entityModel->getId()) {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes(),
                'attr' => [
                    'disabled' => 'disabled'
                ]
            ]);
            $builder->add('attributes', 'attribute_collection', [
                'type' => 'entity_model_attribute',
                'allow_add' => true,
                'widget_add_btn' => [
                    'label' => 'Add attribute'
                ]
            ]);
        } else {
            $builder->add('type', 'choice', [
                'choices' => $this->getSortedEntityTypes()
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

    public function setEntityFactory(EntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    protected function getSortedEntityTypes()
    {
        $sorted = [];
        $entityTypes = $this->entityFactory->getEntityTypes();

        /** @var EntityType $entityType */
        foreach ($entityTypes as $entityType) {
            $sorted[$entityType->getName()] = $entityType;
        }
        return $entityTypes;
    }
}