<?php

namespace BlueBear\DungeonBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\DungeonBundle\Entity\ORM\Character;
use BlueBear\DungeonBundle\Form\Type\CombatType;
use BlueBear\EngineBundle\Event\EngineEvent;
use BlueBear\EngineBundle\Event\EventRequest;
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
            ->getRepository('BlueBearDungeonBundle:ORM\Character')
            ->findAll();

        if (count($characters) == 0) {
            $this->addFlash('info', 'You should create characters first');
            return $this->redirectToRoute('bluebear.dungeon.selectRace');
        }
        $sortedCharacters = [];
        /** @var Character $character */
        foreach ($characters as $character) {
            $sortedCharacters[$character->id] = $character->name;
        }
        $form = $this->createForm(new CombatType(), null, [
            'entities' => $sortedCharacters
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $figher1 = $this
                ->getEntityManager()
                ->getRepository('BlueBearDungeonBundle:ORM\Character')
                ->find($data[0]);
            $figher2 = $this
                ->getEntityManager()
                ->getRepository('BlueBearDungeonBundle:ORM\Character')
                ->find($data[1]);

            $eventRequest = new GameCreateRequest();
            $engineEvent = new EngineEvent($eventRequest);

            $this->get('event_dispatcher')->dispatch(EngineEvent::ENGINE_GAME_CREATE, $engineEvent);
        }
        return [
            'form' => $form->createView()
        ];
    }
}
