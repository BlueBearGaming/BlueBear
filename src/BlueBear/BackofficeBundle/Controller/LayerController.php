<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Manager\LayerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LayerController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $layers = $this->getLayerManager()->findAll();

        return [
            'layers' => $layers
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        if ($id = $request->get('id')) {
            $layer = $this->getLayerManager()->find($id);
            $this->redirect404Unless($layer);
        } else {
            $layer = new Layer();
        }
        $form = $this->createForm('layer', $layer);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getLayerManager()->save($layer);
            $this->setMessage('Pencil successfully saved');
            return $this->redirect('@bluebear_backoffice_layer');
        }
        return [
            'form' => $form->createView()
        ];
    }

    public function deleteAction(Request $request)
    {
        $layer = $this->getLayerManager()->find($id = $request->get('id'));
        $this->redirect404Unless($layer, 'Layer not found? id: ' . $id);
        $this->getLayerManager()->delete($layer);

        return $this->redirect('@bluebear_backoffice_layer');
    }

    /**
     * @return LayerManager
     */
    protected function getLayerManager()
    {
        return $this->get('bluebear.manager.layer');
    }
}