<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Manager\MapManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MapController extends Controller
{
    use ControllerBehavior;

    /**
     * List all maps
     *
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
     * Edit a map
     *
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        if ($id = $request->get('id')) {
            $map = $this->getMapManager()->findMap($id);
            $this->redirect404Unless($map);
        } else {
            $map = new Map();
        }
        $form = $this->createForm('map', $map);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('bluebear.manager.pencil_set')->removeFromMap($map);
            $this->getMapManager()->saveMap($map);
            $this->setMessage('Map successfully saved');

            return $this->redirect('@bluebear_backoffice_map');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Delete a map
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $map = $this->getMapManager()->find($id = $request->get('id'));
        $this->redirect404Unless($map, 'Map not found? id: ' . $id);
        $this->getMapManager()->delete($map);

        return $this->redirect('@bluebear_backoffice_map');
    }

    /**
     * Return map manager
     *
     * @return MapManager
     */
    protected function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
}