<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\AdminBundle\Admin\AdminFactory;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class GenericController extends Controller
{
    use ControllerTrait;

    protected $entityClass;

    protected $formType;

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {
        $admin = $this->get('bluebear.admin.factory')->getAdminFromRequest($request);
        // TODO adding pagination and filters
        // check permissions and actions
        $this->forward404Unless($admin->isActionGranted($admin->getCurrentAction()->getName(), $this->getUser()->getRoles()),
            'User not allowed for action ' . $admin->getCurrentAction()->getName());
        // set entities list
        $admin->setEntities($admin->getRepository()->findAll());

        return [
            'admin' => $admin,
            'action' => $admin->getCurrentAction()
        ];
    }

    /**
     * @Template("BlueBearAdminBundle:Generic:edit.html.twig")
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        // get admin from request parameters
        $admin = $this->get('bluebear.admin.factory')->getAdminFromRequest($request);
        // create entity
        $entity = $admin->createEntity();
        // create form
        $form = $this->createForm($admin->getFormType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // save entity
            $admin->saveEntity();
            // inform user everything went fine
            $this->setMessage('bluebear.admin.' . $admin->getName() . '.saved');
            // redirect to list
            return $this->redirect($this->generateUrl($admin->generateRouteName('list')));
        }
        return [
            'admin' => $admin,
            'form' => $form->createView()
        ];
    }

    /**
     * @Template("BlueBearAdminBundle:Generic:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        // get admin from request parameters
        $admin = $this->get('bluebear.admin.factory')->getAdminFromRequest($request);
        // find entity
        $entity = $admin->findEntity('id', $request->get('id'));
        // create form
        $form = $this->createForm($admin->getFormType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // save entity
            $admin->saveEntity();
            // inform user everything went fine
            $this->setMessage('bluebear.admin.' . $admin->getName() . '.saved');
            // redirect to list
            return $this->redirect($this->generateUrl($admin->generateRouteName('list')));
        }
        return [
            'admin' => $admin,
            'form' => $form->createView()
        ];
    }

    public function deleteAction(Request $request)
    {
        // get admin from request parameters
        $admin = $this->get('bluebear.admin.factory')->getAdminFromRequest($request);
        $admin->findEntity('id', $request->get('id'));
        $admin->deleteEntity();
        // inform user everything went fine
        $this->setMessage('bluebear.admin.' . $admin->getName() . '.removed');
        // redirect to list
        return $this->redirect($this->generateUrl($admin->generateRouteName('list')));
    }

    /**
     * @return AdminFactory
     */
    protected function getAdminFactory()
    {
        return $this->getContainer()->get('bluebear.admin.factory');
    }
}