<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\AdminBundle\Admin\Admin;
use BlueBear\AdminBundle\Admin\AdminFactory;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $admin = $this->getAdminFromRequest($request);
        $action = $requestParameters[1];

        // check permissions and actions
        $this->forward404Unless($admin->isActionGranted($action, $this->getUser()->getRoles()),
            'User not allowed for action '.$action);
        // set entities list
        $admin->setEntities($admin->getRepository()->findAll());

        return [
            'admin' => $admin
        ];
    }

    public function editAction(Request $request)
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

    public function createAction()
    {

    }

    public function deleteAction()
    {

    }

    /**
     * @param Request $request
     * @return Admin
     * @throws Exception
     */
    protected function getAdminFromRequest(Request $request)
    {
        $requestParameters = explode('/', $request->getPathInfo());
        // remove empty string
        array_shift($requestParameters);
        // get configured admin
        return $this->getAdminFactory()->getAdmin($requestParameters[0]);
    }

    protected function getActionFromRequest(Request $request)
    {
        $requestParameters = explode('/', $request->getPathInfo());
        // remove empty string
        array_shift($requestParameters);
    }

    /**
     * @return AdminFactory
     */
    protected function getAdminFactory()
    {
        return $this->getContainer()->get('bluebear.admin.factory');
    }
}