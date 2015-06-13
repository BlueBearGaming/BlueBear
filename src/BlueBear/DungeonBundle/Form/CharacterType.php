<?php

namespace BlueBear\DungeonBundle\Form;

use BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass;
use BlueBear\DungeonBundle\Entity\Race\Race;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use BlueBear\DungeonBundle\UnitOfWork\UnitOfWork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CharacterType extends AbstractType
{
    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $step = $options['step'];

        if ($step == 1) {
            $builder
                ->add('race', 'choice', [
                    'expanded' => true,
                    'choices' => $this->getRaces(),
                    'attr' => [
                        'class' => 'race-container'
                    ]
                ]);
        } else if ($step == 2) {
            $builder->add('race', 'hidden');
            $builder->add('class', 'choice', [
                'expanded' => true,
                'choices' => $this->getClasses(),
                'attr' => [
                    'class' => 'class-container'
                ]
            ]);
        } else if ($step == 3) {
            $builder->add('race', 'hidden');
            $builder->add('class', 'hidden');
            $builder->add('ability', new AbilityType());
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'step' => 1
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

    /**
     * @param UnitOfWork $unitOfWork
     */
    public function setUnitOfWork($unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    protected function getRaces()
    {
        $sorted = [];
        $races = $this->unitOfWork->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\Race\Race'));
        /** @var Race $race */
        foreach ($races as $race) {
            $sorted[$race->code] = $race->label;
        }
        return $sorted;
    }

    protected function getClasses()
    {
        $sorted = [];
        $classes = $this->unitOfWork->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass'));
        /** @var CharacterClass $class */
        foreach ($classes as $class) {
            $sorted[$class->code] = $class->label;
        }
        return $sorted;
    }
}
