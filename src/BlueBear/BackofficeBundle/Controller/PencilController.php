<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\PencilManager;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $pencilSets = $this->getPencilSetManager()->findAll();

        return [
            'pencils' => $pencils,
            'pencilSets' => $pencilSets
        ];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request)
    {
        if ($id = $request->get('id')) {
            $pencil = $this->getPencilManager()->find($id);
            $this->redirect404Unless($pencil);
        } else {
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
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPencilSetAction(Request $request)
    {
        if ($id = $request->get('id')) {
            $pencilSet = $this->getPencilSetManager()->find($id);
        } else {
            $pencilSet = new PencilSet();
        }
        $form = $this->createForm('pencil_set', $pencilSet);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getPencilSetManager()->save($pencilSet);
            $response = $this->redirect('@bluebear_backoffice_pencil');

            if ($request->isXmlHttpRequest()) {
                $pencilSets = $this->getPencilSetManager()->findAll();
                $data = [];
                /**
                 * @var pencilSet $pencilSet
                 */
                foreach ($pencilSets as $pencilSet) {
                    $data[] = [
                        'id' => $pencilSet->getId(),
                        'label' => $pencilSet->getLabel()
                    ];
                }
                $response = new JsonResponse([
                    'code' => 'ok',
                    'data' => $data
                ]);
            }
            return $response;
        }
        $response = $this->render('BlueBearBackofficeBundle:Pencil:editPencilSet.html.twig', [
                'form' => $form->createView()]
        );

        if ($request->isXmlHttpRequest()) {
            $ajaxResponse = $this->render('BlueBearBackofficeBundle:Pencil:editPencilSetAjax.html.twig', [
                    'form' => $form->createView()]
            );
            $response = new JsonResponse([
                'code' => 'ko',
                'data' => $ajaxResponse->getContent()
            ]);
        }
        return $response;
    }

    /**
     * @return PencilManager
     */
    protected function getPencilManager()
    {
        return $this->get('bluebear.manager.pencil');
    }

    /**
     * @return PencilSetManager
     */
    protected function getPencilSetManager()
    {
        return $this->get('bluebear.manager.pencil_set');
    }
}