<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PencilSetController extends Controller
{
    use ControllerTrait;

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
     * @param PencilSet $pencilSet
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