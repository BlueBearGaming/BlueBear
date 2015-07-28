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
            $builder->add('attributes', new AbilityType(), [
                'attributes' => $options['attributes']
            ]);
        } else if ($step == 5) {
            $builder->add('race', 'hidden');
            $builder->add('class', 'hidden');
            $builder->add('attributes', 'hidden');
            $builder->add('name', 'text');
            $builder->add('hitPoints', 'text', [
                'read_only' => true
            ]);
            $builder->add('gender', 'choice',[
                'expanded' => true,
                'choices' => [
                    'm' => 'Man',
                    'f' => 'Woman',
                    'o' => 'Other',
                ]
            ]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'step' => 1,
            'attributes' => []
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
