<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\ImageManager;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\CoreBundle\Manager\PencilManager;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use BlueBear\CoreBundle\Manager\ResourceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param Request $request
     * @return array|RedirectResponse
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
            return $this->redirect('@bluebear_backoffice_pencil');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $pencil = $this->getPencilManager()->find($id = $request->get('id'));
        $this->redirect404Unless($pencil);
        $this->getPencilManager()->delete($pencil);

        return $this->redirect('@bluebear_backoffice_pencil');
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
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
            $file = $form->get('file')->getData();
            // upload new file
            if ($file) {
                if ($pencilSet->getSprite()) {
                    $image = $pencilSet->getSprite();
                } else {
                    $image = new Image();
                    $image->setName('sprite_' . $pencilSet->getName());
                    $pencilSet->setSprite($image);
                }
                // save image object
                $this->getImageManager()->save($image);
                // upload resource
                $this->getResourceManager()->upload($file, $image);
                $this->setMessage('Image has been successfully uploaded');
            }
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function deletePencilSetAction(Request $request)
    {
        $pencilSet = $this->getPencilSetManager()->find($id = $request->get('id'));
        $this->redirect404Unless($pencilSet);
        $this->getPencilSetManager()->delete($pencilSet);

        return $this->redirect('@bluebear_backoffice_pencil');
    }

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function selectPencilSetAction(Request $request)
    {
        $mapName = $request->get('mapName');
        $pencilSetId = $request->get('pencilSetId');

        /**
         * @var Map $map
         * @var PencilSet $pencilSet
         */
        $pencilSet = $this->getPencilManager()->find($pencilSetId);
        $map = $this->getMapManager()->findOneByName($mapName);
        $map->addPencilSets($pencilSet);
        $this->getMapManager()->save($map);

        $form = $this->createForm('pencil_set_list');
        $form->handleRequest($request);

        if ($form->isValid()) {

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

    /**
     * @return PencilSetManager
     */
    protected function getPencilSetManager()
    {
        return $this->get('bluebear.manager.pencil_set');
    }

    /**
     * @return MapManager
     */
    protected function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }
    
        /**
     * @return ResourceManager
     */
    protected function getResourceManager()
    {
        return $this->get('bluebear.manager.resource');
    }

    /**
     * @return ImageManager
     */
    protected function getImageManager()
    {
        return $this->get('bluebear.manager.image');
    }
}