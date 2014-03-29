<?php


namespace BlueBear\BackofficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SpriteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('file', 'file', [
            'label' => 'Upload a sprite'
        ]);
    }

    public function getName()
    {
        return 'sprite';
    }
} 