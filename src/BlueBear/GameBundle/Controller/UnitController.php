<?php

namespace BlueBear\GameBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\GameBundle\Entity\EntityModel;
use BlueBear\GameBundle\Entity\UnitInstance;
use BlueBear\GameBundle\Manager\UnitInstanceManager;
use BlueBear\GameBundle\Manager\UnitModelManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UnitController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $unitInstances = $this->getUnitInstanceManager()->findAll();
        $unitModels = $this->getUnitModelManager()->findAll();

        return [
            'unitInstances' => $unitInstances,
            'unitModels' => $unitModels
        ];
    }

    /**
     * @Template("BlueBearGameBundle:Unit:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createInstanceAction(Request $request)
    {
        return $this->editInstanceAction($request, new UnitInstance());
    }

    /**
     * @Template("BlueBearGameBundle:Unit:edit.html.twig")
     * @param Request $request
     * @param UnitInstance $unit
     * @return array|RedirectResponse
     */
    public function editInstanceAction(Request $request, UnitInstance $unit)
    {
        $form = $this->createForm('unit_instance', $unit);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUnitInstanceManager()->save($unit);
            $this->setMessage('Unit successfully saved');
            return $this->redirect('@bluebear_backoffice_unit');
        }
        return [
            'form' => $form->createView()
        ];
    }

    public function deleteInstanceAction()
    {

    }

    /**
     * @Template("BlueBearGameBundle:Unit:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createModelAction(Request $request)
    {
        return $this->editModelAction($request, new EntityModel());
    }

    /**
     * @Template("BlueBearGameBundle:Unit:edit.html.twig")
     * @param Request $request
     * @param EntityModel $unitModel
     * @return array|RedirectResponse
     */
    public function editModelAction(Request $request, EntityModel $unitModel)
    {
        $entityTypes = $this->get('bluebear.game.unit_factory');
        $form = $this->createForm('unit_model', $unitModel);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUnitInstanceManager()->save($unitModel);
            $this->setMessage('Unit successfully saved');
            return $this->redirect('@bluebear_backoffice_unit');
        }
        return [
            'form' => $form->createView()
        ];
    }

    public function deleteModelAction()
    {

    }

    /**
     * @return UnitInstanceManager
     */
    protected function getUnitInstanceManager()
    {
        return $this->get('bluebear.manager.unit_instance');
    }

    /**
     * @return UnitModelManager
     */
    protected function getUnitModelManager()
    {
        return $this->get('bluebear.manager.unit_model');
    }
}