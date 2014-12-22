<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentationController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}