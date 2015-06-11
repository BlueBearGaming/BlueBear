<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
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
        $races = $this
            ->get('bluebear.engine.unit_of_work')
            ->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\Race\Race'));
        $form = $this->createForm('dungeon_character');
        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirectToRoute('bluebear.dungeon.selectClass', $form->getData());
        }
        return [
            'form' => $form->createView(),
            'races' => $races
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @param $race
     * @return array
     */
    public function selectClassAction(Request $request, $race)
    {
        $classes = $this
            ->get('bluebear.engine.unit_of_work')
            ->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass'));
        $form = $this->createForm('dungeon_character', [
            'race' => $race
        ], [
            'step' => 2
        ]);
        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'classes' => $classes
        ];
    }
}
