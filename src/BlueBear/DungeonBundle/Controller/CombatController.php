<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Game\Game;
use BlueBear\CoreBundle\Entity\Game\GameAction;
use BlueBear\DungeonBundle\Form\Type\CombatType;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
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
            $eventRequest = new GameCreateRequest();
            $gameEvent = new GameEvent($eventRequest);
            $this
                ->get('event_dispatcher')
                ->dispatch(EngineEvent::ENGINE_GAME_CREATE, $gameEvent);

            $game = $gameEvent->getGame();
            $initAction = new GameAction();
            $initAction->setName('bluebear.combat.init');
            $initAction->setAction('bluebear.combat.init');
            $initAction->setGame($game);
            $this
                ->get('doctrine')
                ->getManager()
                ->persist($initAction);
            $game->addActionToStack($initAction);
            $this
                ->get('bluebear.manager.game')
                ->save($game);

            return $this->redirectToRoute('bluebear.dungeon.combat.run', [
                'gameId' => $game->getId()
            ]);
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     * @param Request $request
     */
    public function runAction(Request $request)
    {
        /** @var Game $game */
        $game = $this
            ->get('bluebear.manager.game')
            ->find($request->get('gameId'));
        $actionStack = $game->getActionStack();
        /** @var GameAction $action */
        foreach ($actionStack as $action) {
            $event = $this->get('bluebear.engine.engine')->run($action->getAction(), '');
            var_dump($event->getResponse());
        }
        die('lol');
    }
}
