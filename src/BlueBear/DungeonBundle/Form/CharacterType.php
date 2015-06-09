<?php

namespace BlueBear\DungeonBundle\Form;

use BlueBear\DungeonBundle\Entity\Race\Race;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use BlueBear\DungeonBundle\UnitOfWork\UnitOfWork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CharacterType extends AbstractType
{
    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('races', 'choice', [
                'expanded' => true,
                'choices' => $this->getRaces(),
                'attr' => [
                    'class' => 'race-container'
                ]
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
            $sorted[$race->code] = $race->code;
        }
        return $sorted;
    }
}
