<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Entity\Dice\DiceLauncher;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     * @throws Exception
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

        if ($form->isValid()) {
            return $this->redirectToRoute('bluebear.dungeon.selectAttributes', $form->getData());
        }

        return [
            'form' => $form->createView(),
            'classes' => $classes
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $race
     * @param $class
     * @return array|RedirectResponse
     * @throws Exception
     */
    public function selectAttributesAction(Request $request, $race, $class)
    {
        $form = $this->createForm('dungeon_character', [
            'race' => $race,
            'class' => $class
        ], [
            'step' => 3
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirectToRoute('bluebear.dungeon.selectSkills', $form->getData());
        }
        // for now, we use the "standard" method to determine abilities points total. We launch 4d6, remove lowest values
        // and sum remaining values until we have 6 values
        $launcher = new DiceLauncher();
        $values = [];

        while (count($values) < 6) {
            $dices = $launcher->launch('4d6');
            $launcher->removeLowest($dices);
            $values[] = $launcher->sum($dices);
       }
        return [
            'form' => $form->createView(),
            'values' => $values
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $race
     * @param $class
     * @return array
     */
    public function selectSkillsAction(Request $request, $race, $class)
    {
        $skills = $this
            ->get('bluebear.engine.unit_of_work')
            ->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass'));
        $form = $this->createForm('dungeon_character', [
            'race' => $race,
            'class' => $class
        ], [
            'step' => 3
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirectToRoute('bluebear.dungeon.selectSkills', $form->getData());
        }
        return [
            'form' => $form->createView(),

        ];
    }
}
