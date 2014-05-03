<?php


namespace BlueBear\CoreBundle\Form\Editor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('pencil');
    }

    public function getName()
    {
        return 'image';
    }
} 