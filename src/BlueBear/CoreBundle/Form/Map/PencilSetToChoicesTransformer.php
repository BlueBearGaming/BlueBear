<?php

namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PencilSetToChoicesTransformer implements DataTransformerInterface
{
    /**
     * @var PencilSetManager $pencilSetManager
     */
    protected $pencilSetManager;

    protected $map;

    public function transform($pencilsSets)
    {
        $sorted = [];

        if (!$pencilsSets) {
            $pencilsSets = [];
        }
        /** @var PencilSet $pencilsSet */
        foreach ($pencilsSets as $pencilsSet) {
            $sorted[] = $pencilsSet->getId();
        }
        return $sorted;
    }

    public function reverseTransform($value)
    {
        $pencilSets = [];

        if (!is_array($value)) {
            throw new TransformationFailedException('Invalid data, expected array of integer');
        }
        foreach ($value as $id) {
            /** @var PencilSet $pencilSet */
            $pencilSet = $this->pencilSetManager->find($id);

            if (!$pencilSet) {
                throw new TransformationFailedException('Pencil set not found (id: ' . $id . ')');
            }
            $pencilSet->setMap($this->map);
            $pencilSets[] = $pencilSet;
        }
        return $pencilSets;
    }

    /**
     * @param PencilSetManager $pencilSetManager
     */
    public function setPencilSetManager($pencilSetManager)
    {
        $this->pencilSetManager = $pencilSetManager;
    }

    public function setMap(Map $map)
    {
        $this->map = $map;
    }
}