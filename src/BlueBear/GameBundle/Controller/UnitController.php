<?php

namespace BlueBear\GameBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\GameBundle\Entity\Unit;
use BlueBear\GameBundle\Manager\UnitManager;
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
        $units = $this->getUnitManager()->findAll();

        return [
            'units' => $units
        ];
    }

    /**
     * @Template("BlueBearGameBundle:Unit:edit.html.twig")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->editAction($request, new Unit());
    }

    /**
     * @Template()
     * @param Request $request
     * @param Unit $unit
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Unit $unit)
    {
        $form = $this->createForm('unit', $unit);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUnitManager()->save($unit);
            $this->setMessage('Unit successfully saved');
            return $this->redirect('@bluebear_backoffice_unit');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @return UnitManager
     */
    protected function getUnitManager()
    {
        return $this->get('bluebear.manager.unit');
    }
}