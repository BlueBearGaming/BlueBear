<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Entity\Race\Race;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function selectRaceAction(Request $request)
    {
        $unitOfWork = $this->get('bluebear.engine.unit_of_work');
        /** @var Race[] $races */
        $races = $unitOfWork->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\Race\Race'));

        $racesArray = [];
        /** @var Race $race */
        foreach ($races as $race) {
            $racesArray[$race->code] = $race->code;
        }
        $form = $this->createForm('dungeon_character');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $race = $form->getData()['races'];

            return $this->redirectToRoute('');
        }

        return [
            'form' => $form->createView(),
            'races' => $races
        ];
    }

    public function selectClassAction(Request $request)
    {

    }
}
