<?php


namespace BlueBear\CoreBundle\Constant\Map;


use BlueBear\CoreBundle\Entity\Map\Map;

class Constant
{
    const LAYER_TYPE_UNIT = 'units';
    const LAYER_TYPE_LAND = 'land';
    const LAYER_TYPE_SELECTION = 'selection';
    const LAYER_TYPE_EVENTS = 'events';

    const PENCIL_TYPE_SELECTION = 'selection';
    const PENCIL_TYPE_EMPTY = 'empty';

    public static function getLayerTypes()
    {
        return [
            'background' => 'Background',
            'land' => 'Land',
            'grid' => 'Grid',
            'selection' => 'Selection',
            'buildings' => 'Buildings',
            'props' => 'Props and Decals',
            'objects' => 'Objects',
            'units' => 'Units',
            'effects' => 'Effects',
            'events' => 'Events',
        ];
    }

    public static function getVirtualLayers()
    {
        return [
            'events',
            'selection'
        ];
    }

    public static function getMapTypes()
    {
        return [
            Map::TYPE_SQUARE => 'Square',
            Map::TYPE_ISOMETRIC => 'Isometric',
            Map::TYPE_HEXAGONAL => 'Hexagonal'
        ];
    }

    public static function getUnitsTypes()
    {
        return [
            'unit_panda' => 'Panda',
            'unit_bad_guy' => 'Bad Guy'
        ];
    }

    public static function getEntityModelAttributesTypes()
    {
        return [
            'string' => 'String',
            'int' => 'integer'
        ];
    }
} 
