<?php

namespace App\Entity\Behavior;

use App\Manager\Map\MapManager;

trait HasMapManager
{
    /**
     * @var MapManager
     */
    protected $mapManager;

    /**
     * @return MapManager
     */
    public function getMapManager()
    {
        return $this->mapManager;
    }

    /**
     * @param MapManager $mapManager
     */
    public function setMapManager($mapManager)
    {
        $this->mapManager = $mapManager;
    }
} 
