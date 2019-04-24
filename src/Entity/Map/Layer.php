<?php

namespace App\Entity\Map;

use App\Entity\Behavior\Descriptionable;
use App\Entity\Behavior\Id;
use App\Entity\Behavior\Label;
use App\Entity\Behavior\Nameable;
use App\Entity\Behavior\Timestampable;
use App\Entity\Behavior\Typeable;

class Layer
{
    use Id, Nameable, Label, Typeable, Descriptionable, Timestampable;

    protected $mapItems;

    protected $index;

    /**
     *
     *
     * @param Layer[] $layers
     * @return bool
     */
    public function isAllowed(array $layers)
    {
        foreach ($layers as $layer) {
            if ($layer->getId() == $this->getId() and
                $layer->getName() == $this->getName()
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getMapItems()
    {
        return $this->mapItems;
    }

    /**
     * @param mixed $mapItems
     */
    public function setMapItems($mapItems)
    {
        $this->mapItems = $mapItems;
    }
}
