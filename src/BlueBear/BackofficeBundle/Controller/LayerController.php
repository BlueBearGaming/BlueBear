<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Manager\LayerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LayerController extends Controller
{
    use ControllerBehavior;

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

    /**
     * @return LayerManager
     */
    protected function getLayerManager()
    {
        return $this->get('bluebear.manager.layer');
    }
}