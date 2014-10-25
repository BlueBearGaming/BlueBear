<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UnitController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $units = [];

        return [
            'units' => $units
        ];
    }
}