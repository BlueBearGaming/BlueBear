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
} 