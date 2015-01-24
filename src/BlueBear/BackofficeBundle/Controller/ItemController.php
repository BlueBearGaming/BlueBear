<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemController extends Controller
{
    use ControllerTrait;

    public function indexAction()
    {

    }

    /**
     *
     * @Template()
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUnCompletedItemsAction()
    {
        $items = $this->get('bluebear.manager.item')->findUncompleted();

        return ['items' => $items];
    }
} 