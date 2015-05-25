<?php

namespace BlueBear\GameChessBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function playAction()
    {
        return [];
    }

    /**
     * @Template()
     */
    public function joinGameAction()
    {
        //$client = $this->get('elephantio_client.default');
        //$client->send('bluebear.engine.joinGame',
        return [
            'data' => [

                'endpoints' => [
                    'createGame' => $this->generateUrl('bluebear_engine_trigger_event', [
                        'eventName' => 'bluebear.engine.createGame'
                    ])
                ],
                'game' => [
                    'mapName' => 'chess_basic_map',
                    'numberOfPlayers' => 2
                ]
            ]
        ];
    }
}
