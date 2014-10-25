<?php

namespace BlueBear\CoreBundle\Manager;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Entity\Map\PencilSetRepository;
use BlueBear\CoreBundle\Manager\Behavior\ManagerBehavior;

class PencilSetManager
{
    use ManagerBehavior;

    public function removeFromMap(Map $map)
    {
        $mapPencilSets = $map->getPencilSets();
        $ids = [];

        /** @var PencilSet $pencilSet */
        foreach ($mapPencilSets as $pencilSet) {
            $ids[] = $pencilSet->getId();
        }
        $pencilSets = $this->getRepository()->findBy([
            'map' => $map
        ]);
        /** @var PencilSet $pencilSet */
        foreach ($pencilSets as $pencilSet) {
            if (!in_array($pencilSet->getId(), $ids)) {
                $pencilSet->setMap(null);
                $this->save($pencilSet, false);
            }
        }
        $this->flush();
    }

    /**
     * Return pencils repository
     *
     * @return PencilSetRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('BlueBear\CoreBundle\Entity\Map\PencilSet');
    }
}