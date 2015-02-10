<?php

namespace BlueBear\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnitInstanceModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        var_dump($options['entity_types']);

        $builder->add('id', 'choice', [
            'choices' => []
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\GameBundle\Entity\EntityModelAttribute',
            'entity_types' => []
        ]);
    }

    public function getName()
    {
        return 'unit_model_attribute';
    }
}