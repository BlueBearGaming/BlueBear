<?php

namespace BlueBear\GameBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\GameBundle\Entity\Unit;
use BlueBear\GameBundle\Manager\UnitManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UnitController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $units = [];

        return [
            'units' => $units
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        if ($id = $request->get('id')) {
            $unit = $this->getUnitManager()->find($id);
            $this->redirect404Unless($unit);
        } else {
            $unit = new Unit();
        }
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