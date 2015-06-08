<?php

namespace BlueBear\DungeonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('races', 'choice', [
                //'expanded' => true,
                //'data_class' => 'BlueBear\DungeonBundle\Entity\Race\Race'
            ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'dungeon_character';
    }
}
