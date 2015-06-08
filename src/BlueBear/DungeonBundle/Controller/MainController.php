<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Entity\Race\Race;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function createCharacterAction()
    {
        $unitOfWork = $this->get('bluebear.engine.unit_of_work');
        /** @var Race[] $races */
        $races = $unitOfWork->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\Race\Race'));

        $racesArray = [];
        /** @var Race $race */
        foreach ($races as $race) {
            $racesArray[$race->getCode()] = $race->getCode();
        }
        $form = $this->createForm('dungeon_character');

        return [
            'form' => $form->createView(),
            'races' => $races
        ];
    }
}
