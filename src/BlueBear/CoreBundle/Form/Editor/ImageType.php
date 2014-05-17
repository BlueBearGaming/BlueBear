<?php


namespace BlueBear\CoreBundle\Form\Editor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('pencil', 'entity', [
            'class' => 'BlueBear\CoreBundle\Entity\Map\Pencil',
            'property' => 'label'
        ]);
    }

    public function getName()
    {
        return 'image';
    }
} 