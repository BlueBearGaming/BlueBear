<?php


namespace BlueBear\CoreBundle\Constant\Map;


class Constant
{
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
} 