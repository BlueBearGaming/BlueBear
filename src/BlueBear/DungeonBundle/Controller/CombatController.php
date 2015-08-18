<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Player;
use BlueBear\DungeonBundle\Form\Type\CombatType;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Event\Data\CombatData;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\GameEvent;
use BlueBear\EngineBundle\Event\Request\GameCreateRequest;
use BlueBear\EngineBundle\Event\Response\ErrorResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        // get units list
        $characters = $this
            ->getEntityManager()
            ->getRepository('BlueBearEngineBundle:EntityModel')
            ->findAll();
        // we should have at least two fighters to fight !
        if (count($characters) < 2) {
            $this->addFlash('info', 'You should create 2 characters at least first');
            return $this->redirectToRoute('bluebear.dungeon.selectRace');
        }
        $players = $this
            ->getEntityManager()
            ->getRepository('BlueBearCoreBundle:Map\Player')
            ->findAll();
        $form = $this->createForm(new CombatType(), null, [
            'entities' => $this->sortCharacters($characters),
            'players' => $this->sortPlayers($players)
        ]);
        $form->handleRequest($request);
        // games in progress
        $games = $this
            ->get('bluebear.manager.game')
            ->findInProgress();

        if ($form->isValid()) {
            $data = $form->getData();
            $eventRequest = new GameCreateRequest();
            $eventRequest->fightersIdsByPlayer = [
                $data['player_1'] => [
                    $data['fighter_1']
                ],
                $data['player_2'] => [
                    $data['fighter_2']
                ]
            ];
            $gameEvent = new GameEvent($eventRequest);
            $this
                ->get('event_dispatcher')
                ->dispatch(EngineEvent::HELL_ARENA_GAME_CREATE, $gameEvent);
            $game = $gameEvent->getGame();


            return $this->redirectToRoute('bluebear.dungeon.combat.run', [
                'gameId' => $game->getId()
            ]);
        }
        return [
            'form' => $form->createView(),
            'games' => $games
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function runAction(Request $request)
    {
        // get first action from the stack
        $currentAction = $this
            ->get('bluebear.manager.game_action')
            ->findFirst($request->get('gameId'));
        $this->forward404Unless($currentAction, 'Action not found for game ' . $request->get('gameId'));
        $event = $this
            ->get('bluebear.engine.engine')
            ->run($currentAction->getAction(), $currentAction->getData());
        $eventResponse = $event->getResponse();

        if ($eventResponse instanceof ErrorResponse) {
            // TODO handle event errors
            print_r($eventResponse);
            die;
        }
        if ($request->isXmlHttpRequest()) {
            if ($event->getName() == EngineEvent::ENGINE_GAME_TURN) {
                /** @var CombatData $data */
                $data = $eventResponse->getData();
                $eventResponse->content = $this->renderView('@BlueBearDungeon/Combat/turn.html.twig', [
                    'data' => $data
                ]);
                $eventResponse->setData($data);
            }
            $serializer = $this->get('jms_serializer');
            $content = $serializer->serialize($eventResponse, 'json');
            $response = new Response();
            $response->setStatusCode(200);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }
        return [
            'response' => $eventResponse,
            'eventData' => $eventResponse->getData()
        ];
    }

    protected function sortCharacters($characters)
    {
        $sortedCharacters = [];
        /** @var EntityModel $character */
        foreach ($characters as $character) {
            $sortedCharacters[$character->getId()] = $character->getLabel();
        }
        return $sortedCharacters;
    }

    protected function sortPlayers($players)
    {
        $sortedPlayers = [];
        /** @var Player $player */
        foreach ($players as $player) {
            $sortedPlayers[$player->getId()] = $player->getPseudonym();
        }
        return $sortedPlayers;
    }
}
