<?php

namespace BlueBear\GameBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\GameBundle\Entity\EntityInstance;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Manager\EntityInstanceManager;
use BlueBear\GameBundle\Manager\EntityModelManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class EntityController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $entityInstances = $this->getEntityInstanceManager()->findAll();
        $entityModels = $this->getEntityModelManager()->findAll();

        return [
            'entityInstances' => $entityInstances,
            'entityModels' => $entityModels
        ];
    }

    /**
     * @Template("BlueBearGameBundle:Entity:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createInstanceAction(Request $request)
    {
        return $this->editInstanceAction($request, new EntityInstance());
    }

    /**
     * @Template("BlueBearGameBundle:Entity:edit.html.twig")
     * @param Request $request
     * @param EntityInstance $entity
     * @return array|RedirectResponse
     */
    public function editInstanceAction(Request $request, EntityInstance $entity)
    {
        $form = $this->createForm('entity_instance', $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityInstanceManager()->save($entity);
            $this->setMessage('Entity successfully saved');
            return $this->redirect('@bluebear_backoffice_entity');
        }
        return [
            'form' => $form->createView()
        ];
    }

    public function deleteInstanceAction()
    {

    }

    /**
     * @Template("BlueBearGameBundle:Entity:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createModelAction(Request $request)
    {
        return $this->editModelAction($request, new EntityModel());
    }

    /**
     * @Template("BlueBearGameBundle:Entity:edit.html.twig")
     * @param Request $request
     * @param EntityModel $entityModel
     * @return array|RedirectResponse
     */
    public function editModelAction(Request $request, EntityModel $entityModel)
    {
        $entityTypes = $this->get('bluebear.game.entity_factory')->getEntityTypes();
        $form = $this->createForm('entity_model', $entityModel, [
            'entity_types' => $entityTypes
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityInstanceManager()->save($entityModel);
            $this->setMessage('Entity successfully saved');
            return $this->redirect('@bluebear_backoffice_entity');
        }
        return [
            'form' => $form->createView()
        ];
    }

    public function deleteModelAction()
    {

    }

    /**
     * @return EntityInstanceManager
     */
    protected function getEntityInstanceManager()
    {
        return $this->get('bluebear.manager.entity_instance');
    }

    /**
     * @return EntityModelManager
     */
    protected function getEntityModelManager()
    {
        return $this->get('bluebear.manager.entity_model');
    }
}