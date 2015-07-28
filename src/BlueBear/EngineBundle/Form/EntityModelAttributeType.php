<?php

namespace BlueBear\EngineBundle\Form;

use BlueBear\EngineBundle\Entity\EntityModelAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityModelAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('value', 'text', [
            'required' => false
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\EngineBundle\Entity\EntityModelAttribute'
        ]);
    }

    public function getName()
    {
        return 'entity_model_attribute';
    }
}
