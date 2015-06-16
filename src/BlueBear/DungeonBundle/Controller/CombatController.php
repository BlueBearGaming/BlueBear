<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Entity\ORM\Character;
use BlueBear\DungeonBundle\Form\Type\CombatType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CombatController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function createAction()
    {
        $characters = $this
            ->getEntityManager()
            ->getRepository('BlueBearDungeonBundle:ORM\Character')
            ->findAll();

        if (count($characters) == 0) {
            $this->addFlash('info', 'You should create characters first');
            return $this->redirectToRoute('bluebear.dungeon.selectRace');
        }
        $data = [];
        /** @var Character $character */
        foreach ($characters as $character) {
            $data[$character->id] = $character->name;
        }
        $form = $this->createForm(new CombatType(), null, [
            'entities' => $data
        ]);
        return [
            'form' => $form->createView()
        ];
    }
}
