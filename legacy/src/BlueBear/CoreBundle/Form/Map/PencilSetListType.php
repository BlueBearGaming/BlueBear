<?php


namespace App\Form\Map;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PencilSetListType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'type' => 'pencil_set',
            'allow_add' => true,
            'options' => [
                'is_choice' => true
            ],
            'label' => false
        ]);
    }

    public function getName()
    {
        return 'pencil_set_list';
    }

    public function getParent()
    {
        return 'collection';
    }
} 
