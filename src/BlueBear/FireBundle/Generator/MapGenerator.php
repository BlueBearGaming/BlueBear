<?php

namespace BlueBear\FireBundle\Generator;

use BlueBear\CoreBundle\Entity\Game\Player\Player;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\ContextRepository;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\MapItemRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Basic map generator for fire game.
 */
class MapGenerator
{
    /**
     * @var ContextRepository
     */
    protected $contextRepository;
    
    /**
     * @var MapItemRepository
     */
    protected $mapItemRepository;

    /**
     * MapGenerator constructor.
     * 
     * @param ContextRepository $contextRepository
     * @param MapItemRepository $mapItemRepository
     */
    public function __construct(ContextRepository $contextRepository, MapItemRepository $mapItemRepository)
    {
        $this->contextRepository = $contextRepository;
        $this->mapItemRepository = $mapItemRepository;
    }

    public function generate(Map $map, Player $player)
    {
        $contextName = sprintf('context-%s-%s', $player->getName(), $player->getId());
        $context = new Context();
        $context->setName($contextName);
        $context->setPlayer($player);
        $context->setMap($map);
        $mapItems = new ArrayCollection();
        $landLayer = $map->getLayerByName('land_layer');

        $this
            ->contextRepository
            ->save($context);

        if (is_numeric($map->getWidth()) && is_numeric($map->getHeight())) {
            $width = 0;

            while ($width < $map->getWidth()) {
                $height = 0;

                while ($width < $map->getHeight()) {
                    $mapItem = new MapItem();
                    $mapItem->setLayer($landLayer);
                    $mapItem->setX($width);
                    $mapItem->setY($height);
                    $mapItem->setContext($context);
                    $this
                        ->mapItemRepository
                        ->save($mapItem);

                    $mapItems->add($mapItem);

                    $height++;
                }
                $width++;
            }
            $context->setMapItems($mapItems);
        }
    }
}
