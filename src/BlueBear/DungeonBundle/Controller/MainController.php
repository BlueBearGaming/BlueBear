<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Entity\Attribute\Attribute;
use BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass;
use BlueBear\DungeonBundle\Entity\Dice\DiceRoller;
use BlueBear\DungeonBundle\Entity\ORM\Character;
use BlueBear\DungeonBundle\UnitOfWork\EntityReference;
use BlueBear\EngineBundle\Entity\EntityInstance;
use BlueBear\EngineBundle\Entity\EntityInstanceAttribute;
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
        $attributes = $this
            ->get('bluebear.engine.unit_of_work')
            ->loadAll(new EntityReference(Attribute::class));
        // for now, we use the "standard" method to determine abilities points total. We launch 4d6, remove lowest values
        // and sum remaining values until we have 6 values
        $launcher = new DiceRoller();
        $values = [];
        $attributeNames = [];
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            $dices = $launcher->roll('4d6');
            $launcher->removeLowest($dices);
            $values[$attribute->code] = $launcher->sum($dices);
            $attributeNames[] = $attribute->code;
        }
        $values['sum'] = array_sum($values);
        $values['remaining'] = 0;
        $form = $this->createForm('dungeon_character', [
            'race' => $race,
            'class' => $class,
            'attributes' => $values
        ], [
            'step' => 3,
            'attributes' => $attributeNames
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('bluebear.dungeon.selectProfile', [
                'race' => $data['race'],
                'class' => $data['class'],
                'attributes' => serialize($data['attributes']),
            ]);
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
        // TODO in progress
//        $skills = $this
//            ->get('bluebear.engine.unit_of_work')
//            ->loadAll(new EntityReference('BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass'));
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

    /**
     * @Template()
     * @param Request $request
     * @param $race
     * @param $class
     * @return array
     */
    public function selectProfileAction(Request $request, $race, $class)
    {
        $attributes = unserialize($request->get('attributes'));
        /** @var CharacterClass $class */
        $class = $this->get('bluebear.engine.unit_of_work')->load(
            new EntityReference('BlueBear\DungeonBundle\Entity\CharacterClass\CharacterClass', $class)
        );
        $lifeDiceCode = $class->attributeSetters->get('character.life')->setter;
        $dice = $this->get('bluebear.dungeon.dice_roller')->roll($lifeDiceCode);
        $form = $this->createForm('dungeon_character', [
            'race' => $race,
            'class' => $class->code,
            'attributes' => $request->get('attributes'),
            'life' => $dice->value
        ], [
            'step' => 5
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $character = new EntityInstance();
            $character->setRaceCode($data['race']);
            $character->setClassCode($data['class']);
            $character->setHitPoints($data['life']);
            $character->setName($data['name']);

            foreach ($data['attributes'] as $attributeName => $attributeValue) {
                $attribute = new EntityInstanceAttribute();
                $attribute->setName($attributeName);
                $attribute->setValue($attributeValue);
                $character->addAttribute($attribute);
            }
            $this->get('doctrine')->getManager()->persist($character);
            $this->get('doctrine')->getManager()->flush($character);

            return $this->redirectToRoute('bluebear_admin_character_list');
        }

        return [
            'form' => $form->createView(),
            'attributes' => $attributes
        ];
    }
}
