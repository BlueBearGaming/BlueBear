<?php

namespace BlueBear\FireBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\FireBundle\Render\Grid\Grid;
use BlueBear\FireBundle\Render\Grid\GridBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    use ControllerTrait;

    public function runAction()
    {
        $builder = new GridBuilder();
        $grid = $builder->build();

        return $this->render('@BlueBearFire/Game/run.html.twig', [
            'cells' => $grid->getCells()
        ]);
    }
}
