<?php

namespace BlueBear\EditorBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Manager\MapManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends Controller
{
    use ControllerTrait;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $maps = $this->getMapManager()->findAll();

        return [
            'maps' => $maps
        ];
    }

    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request)
    {
        /** @var Map $map */
        $map = $this
            ->getMapManager()
            ->findOneBy(['name' => $request->get('mapName')]);
        $context = $map
            ->getContexts()
            ->first();
        $jikpozeOptions = [
            'endPoint' => $this->generateUrl('bluebear_engine_trigger_event', [], UrlGeneratorInterface::ABSOLUTE_URL) . '/',
            'edition' => (bool) $request->get('edition'),
            'resourceBasePath' => $this->get('blue_bear_file_upload.twig.upload_extension')->resource_path(null, true),
            'layerSelectorName' => 'bluebear_map_editor[selected_layer]',
            'pencilSelectorName' => 'bluebear_map_editor[selected_pencil]',
            'contextId' => $context->getId(),
            'socketIOUri' => $request->getHost() . ':8000',
        ];

        return [
            'context' => $context,
            'jikpozeOptions' => $jikpozeOptions,
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function deleteAction(Request $request)
    {
        $map = $this->getMapManager()->find($request->get('id'));
        $this->forward404Unless($map, 'Map not found');
        $this->getMapManager()->delete($map);

        return $this->redirect('@bluebear_editor_homepage');
    }

    /**
     * @return MapManager
     */
    protected function getMapManager()
    {
        return $this->get('bluebear.manager.map');
    }

    protected function createResponse($code, $data, $statusCode = 200)
    {
        $response = new JsonResponse();
        $response->setStatusCode($statusCode);
        $response->setEncodingOptions(JSON_PRETTY_PRINT);
        $response->setData([
            'code' => $code,
            'data' => $data
        ]);
        return $response;
    }
}
