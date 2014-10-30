<?php

namespace BlueBear\EditorBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Manager\MapManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $form = $this->createForm('engine_event_test');

        $this->redirect404Unless($map, 'Map not found');

        return [
            'map' => $map,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function deleteAction(Request $request)
    {
        $map = $this->getMapManager()->find($request->get('id'));
        $this->redirect404Unless($map, 'Map not found');
        $this->getMapManager()->delete($map);

        return $this->redirect('@bluebear_editor_homepage');
    }

    /**
     * @return MapManager
     */
    protected function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
}
