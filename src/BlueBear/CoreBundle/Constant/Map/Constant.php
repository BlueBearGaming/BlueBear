<?php


namespace BlueBear\CoreBundle\Constant\Map;


class Constant
{
    const LAYER_TYPE_UNIT = 'units';
    const LAYER_TYPE_LAND = 'land';

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

    public static function getMapTypes()
    {
        return [
            'square' => 'Square',
            'isometric' => 'Isometric',
            'hexagonal' => 'Hexagonal'
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
