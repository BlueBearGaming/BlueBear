<?php

namespace BlueBear\FireBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\FireBundle\Render\Grid\GridBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    use ControllerTrait;

    public function runAction()
    {
        $map = $this
            ->get('bluebear.manager.map')
            ->findOneBy([
                'name' => 'fire_map_1'
            ]);

        $builder = new GridBuilder($map);
        $grid = $builder->build();

        return $this->render('@BlueBearFire/Game/run.html.twig', [
            'map' => $grid,
            'client' => $this->get('bluebear.io.client')
        ]);
    }
}
