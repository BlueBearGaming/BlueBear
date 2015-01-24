<?php

namespace BlueBear\AdminBundle\Controller;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GenericController extends Controller
{
    use ContainerTrait;

    protected $entityClass;

    protected $formType;

    public function listAction()
    {

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
}