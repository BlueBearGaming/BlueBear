<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Manager\LayerManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LayerToIdTransformer implements DataTransformerInterface
{
    /**
     * @var LayerManager
     */
    protected $layerManager;

    public function __construct(LayerManager $layerManager)
    {
        $this->layerManager = $layerManager;
    }

    /**
     * @param Layer $layer
     * @return mixed|void
     */
    public function transform($layer)
    {
        $value = '';

        if ($layer) {
            $value = $layer->getId();
        }
        return $value;
    }

    public function reverseTransform($id)
    {
        $layer = $this->layerManager->find($id);

        if (!$layer) {
            throw new TransformationFailedException('Invalid layer id : ' . $id);
        }
        return $layer;
    }
} 