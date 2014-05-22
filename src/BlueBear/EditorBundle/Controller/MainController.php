<?php

namespace BlueBear\EditorBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Manager\MapManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $maps = $this->getMapManager()->findAll();

        return [
            'maps' => $maps
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request)
    {
        $map = $this->getMapManager()->find($request->get('id'));
        $this->redirect404Unless($map, 'Map not found');

        return [
            'map' => $map
        ];
    }

    /**
     * @return MapManager
     */
    protected function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
}
