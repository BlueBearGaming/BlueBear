<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Entity\Map\PencilSet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PencilSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('label');
        $builder->add('type', 'choice', [
            'choices' => PencilSet::getPencilSetType()
        ]);
        $builder->add('file', 'file', [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'help_block' => 'File to upload',
        ]);
    }

    public function getName()
    {
        return 'pencil_set';
    }
} 