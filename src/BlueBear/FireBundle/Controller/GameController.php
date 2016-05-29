<?php

namespace BlueBear\FireBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Game\Save\Save;
use BlueBear\FireBundle\Form\Type\SaveType;
use BlueBear\FireBundle\Render\Grid\GridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function runAction()
    {
        // find existing save for player
        $saves = $this
            ->get('save_repository')
            ->findForPlayer($this->getCurrentPlayer());

        $save = new Save();
        $form = $this->createForm(SaveType::class, $save);


        return [
            'form' => $form->createView()
        ];
    }

    protected function getCurrentPlayer()
    {
        return $this
            ->get('player_repository')
            ->findOneBy([
                'name' => 'fire_player'
            ]);
    }
}
