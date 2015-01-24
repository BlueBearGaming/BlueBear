<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\PencilManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PencilController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $pencils = $this->getPencilManager()->findAll();
        return [
            'pencils' => $pencils,
        ];
    }

    /**
     * @Template("BlueBearBackofficeBundle:Pencil:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->editAction($request, new Pencil);
    }

    /**
     * @Template()
     * @param Request $request
     * @param Pencil $pencil
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Pencil $pencil)
    {
        $form = $this->createForm('pencil', $pencil);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getPencilManager()->save($pencil);
            $this->setMessage('Pencil successfully saved');
            return $this->redirect('@bluebear_backoffice_pencil');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Pencil $pencil
     * @return RedirectResponse
     */
    public function deleteAction(Pencil $pencil)
    {
        $this->getPencilManager()->delete($pencil);
        return $this->redirect('@bluebear_backoffice_pencil');
    }

    /**
     * @return PencilManager
     */
    protected function getPencilManager()
    {
        return $this->get('bluebear.manager.pencil');
    }
}