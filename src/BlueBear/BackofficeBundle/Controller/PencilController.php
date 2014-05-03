<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\PencilManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class PencilController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $pencils = $this->getPencilManager()->findAll();

        return [
            'pencils' => $pencils
        ];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request)
    {
        $pencil = $this->getPencilManager()->find($request->get('id'));

        if (!$pencil) {
            $pencil = new Pencil();
        }
        $form = $this->createForm('pencil', $pencil);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getPencilManager()->save($pencil);
            $this->setMessage('Pencil successfully saved');
            return '';
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @return PencilManager
     */
    protected function getPencilManager()
    {
        return $this->get('bluebear.manager.pencil');
    }
} 