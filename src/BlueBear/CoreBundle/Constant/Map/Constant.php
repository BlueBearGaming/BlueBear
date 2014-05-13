<?php


namespace BlueBear\CoreBundle\Constant\Map;


class Constant
{
    public static function getLayerType()
    {
        return [
            'background' => 'Background',
            'land' => 'Land',
            'unit' => 'Unit',
            'event' => 'Event'
        ];
    }

    public static function getPencilType()
    {
        return [
            'background' => 'Background',
            'land' => 'Land',
            'unit' => 'Unit'
        ];
    }

    public static function getMapType()
    {
        return [
            'campaign' => 'Campaign',
            'mini-game' => 'Mini Game',
            'other' => 'Autres'
        ];
    }
} 