<?php

namespace BlueBear\EditorBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Manager\MapManager;
use BlueBear\EngineBundle\Engine\Engine;
use BlueBear\EngineBundle\Event\EngineEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use stdClass;
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
     * @todo use context instead of map
     * @Template()
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request)
    {
        /** @var Engine $engine */
//        $engine = $this->get('bluebear.engine.engine');
//        $data = new stdClass();
//        $data->mapName = $request->get('mapName');
//        $event = $engine->run(EngineEvent::ENGINE_ON_CONTEXT_LOAD, $data);
        /** @var Map $map */
        $map = $this->getMapManager()->findOneBy(['name' => $request->get('mapName')]);
        $context = $map->getContexts()->first();
        $jikpozeOptions = [
            'endPoint' => $this->generateUrl('bluebear_engine_trigger_event', [], UrlGeneratorInterface::ABSOLUTE_URL) . '/',
            'editionMode' => true,
            'resourceBasePath' => $this->get('bluebear.twig.utils')->getResourcePath(null, true),
            'layerSelectorName' => 'bluebear_map_editor[selected_layer]',
            'pencilSelectorName' => 'bluebear_map_editor[selected_pencil]',
            'contextId' => $context->getId(),
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
        $this->redirect404Unless($map, 'Map not found');
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
