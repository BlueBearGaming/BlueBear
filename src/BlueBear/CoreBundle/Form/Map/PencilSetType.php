<?php


namespace BlueBear\CoreBundle\Form\Map;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PencilSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('label');
    }

    public function getName()
    {
        return 'pencil_set';
    }
} 