<?php


namespace BlueBear\CoreBundle\Constant\Map;


class Constant
{
    public static function getLayerType()
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

    public static function getPencilType()
    {
        return [
            'background' => 'Background',
            'land' => 'Land',
            'building' => 'Building',
            'prop' => 'Props',
            'object' => 'Object',
            'unit' => 'Unit',
            'effect' => 'Effect',
        ];
    }

    public static function getMapType()
    {
        return [
            'square' => 'Square',
            'isometric' => 'Isometric',
            'hexagonal' => 'Hexagonal'
        ];
    }
} 