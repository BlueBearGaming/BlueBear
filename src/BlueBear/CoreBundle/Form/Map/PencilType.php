<?php


namespace BlueBear\CoreBundle\Form\Map;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PencilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('pencilSet');
        $builder->add('allowedLayers');
        $builder->add('imageX');
        $builder->add('imageY');
    }

    public function getName()
    {
        return 'pencil';
    }
} 