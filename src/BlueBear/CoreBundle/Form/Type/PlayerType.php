<?php

namespace BlueBear\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('pseudonym', 'text')
            ->add('enabled', 'checkbox');
    }

    public function getName()
    {
        return 'player';
    }
}
