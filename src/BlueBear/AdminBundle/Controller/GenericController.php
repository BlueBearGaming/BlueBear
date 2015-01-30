<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\AdminBundle\Admin\Action;
use BlueBear\AdminBundle\Admin\Admin;
use BlueBear\AdminBundle\Admin\AdminFactory;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Exception;
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
        $action = $this->getActionFromRequest($request, $admin);
        $admin->setCurrentAction($action);
        // TODO adding pagination
        // check permissions and actions
        $this->forward404Unless($admin->isActionGranted($action->getName(), $this->getUser()->getRoles()),
            'User not allowed for action ' . $action->getName());
        // set entities list
        $admin->setEntities($admin->getRepository()->findAll());

        return [
            'admin' => $admin,
            'action' => $action
        ];
    }

    /**
     * @Template("BlueBearAdminBundle:Generic:edit.html.twig")
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $admin = $this->get('bluebear.admin.factory')->getAdminFromRequest($request);
        $action = $this->getActionFromRequest($request, $admin);
        $entity = $admin->getEntityNamespace();

        $admin->setEntity(new $entity);
        $admin->setCurrentAction($action);
        $form = $this->createForm($admin->getFormType(), $admin->getEntity());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityManager()->persist($admin->getEntity());
            $this->getEntityManager()->flush($admin->getEntity());

            $this->setMessage('bluebear.admin.' . $admin->getName() . '.saved');
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
        $admin = $this->get('bluebear.admin.factory')->getAdminFromRequest($request);
        $admin->setEntity($admin->getRepository()->find($request->get('id')));
        $admin->setCurrentAction($this->getActionFromRequest($request, $admin));

        $form = $this->createForm($admin->getFormType(), $admin->getEntity());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityManager()->persist($admin->getEntity());
            $this->getEntityManager()->flush($admin->getEntity());
            $this->setMessage('bluebear.admin.' . $admin->getName() . '.saved');
            return $this->redirect('@' . $admin->generateRouteName('list'));
        }
        return [
            'admin' => $admin,
            'form' => $form->createView()
        ];
    }

    public function deleteAction()
    {

    }

    /**
     * @param Request $request
     * @param Admin $admin
     * @return Action
     * @throws Exception
     */
    protected function getActionFromRequest(Request $request, Admin $admin)
    {
        $requestParameters = explode('/', $request->getPathInfo());
        // remove empty string
        array_shift($requestParameters);

        return $admin->getAction($requestParameters[1]);
    }

    /**
     * @return AdminFactory
     */
    protected function getAdminFactory()
    {
        return $this->getContainer()->get('bluebear.admin.factory');
    }
}