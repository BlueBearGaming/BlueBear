<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentationController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}