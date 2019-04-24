<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function homepageAction()
    {
        // TODO count number of entities in backoffice
        //$unitsCount = $this->get('bluebear.manager.units');
        $maps = $this->get('bluebear.manager.map')->count();
        return [];
    }
}
