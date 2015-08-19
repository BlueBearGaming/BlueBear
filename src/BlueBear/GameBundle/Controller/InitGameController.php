<?php

namespace BlueBear\GameBundle\Controller;

use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InitGameController extends Controller
{
    use ControllerTrait;

    /**
     * Create a new BlueBear game
     *
     * @Template()
     */
    public function initScreenAction()
    {
        return [];
    }

    /**
     * @Template()
     */
    public function mainMenuAction()
    {
        return [];
    }

    /**
     * @Template()
     * @param Request $request
     */
    public function newGameAction(Request $request)
    {
        // TODO loading first map in progress
        $map = $this->get('bluebear.manager.map')->findOneBy([
            'name' => 'bluebear_map_0'
        ]);
        $this->forward404Unless($map, 'Map not found');

        $clientOptions = [
            'endPoint' => $this->generateUrl('bluebear_engine_trigger_event', [], UrlGeneratorInterface::ABSOLUTE_URL) . '/',
            'edition' => false,
            'resourceBasePath' => $this->get('blue_bear_file_upload.twig.upload_extension')->resource_path(null, true),
            'layerSelectorName' => 'bluebear_map_editor[selected_layer]',
            'pencilSelectorName' => 'bluebear_map_editor[selected_pencil]',
            'contextId' => $context->getId(),
            'socketIOUri' => $request->getHost() . ':8000',
        ];
    }
}
