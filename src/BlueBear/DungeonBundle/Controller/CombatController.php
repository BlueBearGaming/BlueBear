<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Form\Type\CombatType;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\Request\GameCreateRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CombatController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $characters = $this
            ->getEntityManager()
            ->getRepository('BlueBearEngineBundle:EntityModel')
            ->findAll();

        if (count($characters) == 0) {
            $this->addFlash('info', 'You should create characters first');
            return $this->redirectToRoute('bluebear.dungeon.selectRace');
        }
        $sortedCharacters = [];
        /** @var EntityModel $character */
        foreach ($characters as $character) {
            $sortedCharacters[$character->getId()] = $character->getLabel();
        }
        $form = $this->createForm(new CombatType(), null, [
            'entities' => $sortedCharacters
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            var_dump($data);
            $eventRequest = new GameCreateRequest();
            //$eventRequest->entityModelIds = [$data['fighter_1'], $data['fighter_1']];
            $engineEvent = new EngineEvent($eventRequest);

            $this->get('event_dispatcher')->dispatch(EngineEvent::ENGINE_GAME_CREATE, $engineEvent);
        }
        return [
            'form' => $form->createView()
        ];
    }
}
