<?php

namespace App\Manager\Map;

use App\Entity\Map\Map;
use App\Entity\Map\PencilSet;
use App\Entity\Map\PencilSetRepository;
use BlueBear\BaseBundle\Behavior\ManagerTrait;

class PencilSetManager
{
    use ManagerTrait;

    public function removeFromMap(Map $map)
    {
        // map save from form items
        $mapPencilSets = $map->getPencilSets();
        $ids = [];

        /** @var PencilSet $pencilSet */
        foreach ($mapPencilSets as $pencilSet) {
            $ids[] = $pencilSet->getId();
        }
        // map pencil set before form edition
        $pencilSets = $this
            ->getRepository()
            ->findByMap($map)
            ->getQuery()
            ->getResult();
        /** @var PencilSet $pencilSet */
        foreach ($pencilSets as $pencilSet) {
            if (!in_array($pencilSet->getId(), $ids)) {
                // removing relation between map and pencil set
                $pencilSet->removeMap($map);
                $this->save($pencilSet);
            }
        }
    }

    /**
     * Return pencils repository
     *
     * @return PencilSetRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository('App\Entity\Map\PencilSet');
    }
}
