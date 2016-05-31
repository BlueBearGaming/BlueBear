<?php

namespace BlueBear\FireBundle\Controller;

use BlueBear\CoreBundle\Entity\Game\Player\Player;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * Display main menu.
     *
     * @Template()
     */
    public function startAction()
    {
        // find existing save for player
        $saves = $this
            ->get('save_repository')
            ->findForPlayer($this->getCurrentPlayer());

        return [
            'saves' => $saves
        ];
    }

    public function newAction()
    {
        
    }

    /**
     * @Template()
     */
    public function runAction()
    {

    }

    /**
     * @return Player|null|object
     */
    protected function getCurrentPlayer()
    {
        $repository = $this->get('player_repository');

        $player = $repository->findOneBy([
            'name' => 'fire_player',
            'user' => $this->getUser()->getId()
        ]);

        // if the user has no player for this game
        if ($player === null) {
            $player = new Player();
            $player->setEnabled(true);
            $player->setIsHuman(true);
            $player->setUser($this->getUser());
            $player->setPseudonym($this->getUser()->getUsername());
            $player->setName($this->getUser()->getUsername());

            $repository->save($player);
        }

        return $player;
    }
}
