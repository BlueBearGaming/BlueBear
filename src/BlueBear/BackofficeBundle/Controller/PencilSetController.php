<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\ImageManager;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\CoreBundle\Manager\PencilManager;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use BlueBear\CoreBundle\Manager\ResourceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PencilSetController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $pencilSets = $this->getPencilSetManager()->findAll();
        return [
            'pencilSets' => $pencilSets
        ];
    }

    /**
     * @Template("BlueBearBackofficeBundle:PencilSet:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->editAction($request, new PencilSet);
    }

    /**
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, PencilSet $pencilSet)
    {
        $form = $this->createForm('pencil_set', $pencilSet);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getPencilSetManager()->save($pencilSet);
            $this->setMessage('Pencil set successfully saved');
            return $this->redirect('@bluebear_backoffice_pencil_set');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @return PencilSetManager
     */
    protected function getPencilSetManager()
    {
        return $this->get('bluebear.manager.pencil_set');
    }
}