<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\AdminBundle\Admin\AdminFactory;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
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
        $requestParameters = explode('/', $request->getPathInfo());
        // remove empty string
        array_shift($requestParameters);
        $admin = $requestParameters[0];
        $action = $requestParameters[1];
        $admin = $this->getAdminFactory()->getAdmin($admin);
        // check permissions and actions
        $this->forward404Unless($admin->isActionGranted($action));

        $entities = $this->getEntityManager()->getRepository($admin->getEntity())->findAll();

        return [
            'admin' => $admin,
            'entities' => $entities
        ];
    }

    public function editAction()
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
     * @return AdminFactory
     */
    protected function getAdminFactory()
    {
        return $this->getContainer()->get('bluebear.admin.factory');
    }
}