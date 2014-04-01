<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ItemController extends Controller
{
    use ControllerBehavior;

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