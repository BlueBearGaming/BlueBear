<?php

namespace BlueBear\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArmyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('label', 'text')
            ->add('player');
    }

    public function getName()
    {
        return 'army';
    }
}
