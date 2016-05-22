<?php

namespace BlueBear\CoreBundle\Path\Converter;

use BlueBear\CoreBundle\Entity\Map\Map;


class ASCIIConverter implements MapConverterInterface
{
    /**
     * Convert a map into an ASCII representation.
     *
     * @param Map $map
     * @return string
     */
    public function convert(Map $map)
    {
        $cells = $map
            ->getLayerByName('ground_layer')
            ->getMapItems();
        $asciiRepresentation = [];
        $minX = 0;
        $maxX = 0;
        $minY = 0;
        $maxY = 0;

        foreach ($cells as $cell) {
            $asciiRepresentation[$cell->getX()][$cell->getY()] = true;

            if ($cell->getX() <= $minX) {
                $minX = $cell->getX();
            }

            if ($cell->getX() >= $maxX) {
                $maxX = $cell->getX();
            }

            if ($cell->getY() <= $minY) {
                $minY = $cell->getY();
            }

            if ($cell->getY() >= $maxY) {
                $maxY = $cell->getY();
            }
        }
        $xIndex = $minX;
        $ascii = '';

        while ($xIndex < $maxX) {
            $yIndex = $minY;

            while ($yIndex < $maxY) {

                if (!empty($asciiRepresentation[$xIndex][$yIndex]) && $asciiRepresentation[$xIndex][$yIndex] === true) {

                    if ($xIndex == 0 && $yIndex == 0) {
                        $ascii .= '>';
                    } else {
                        $ascii .= ' ';
                    }
                }
                $yIndex++;
            }
            $ascii .= "\n";
            $xIndex++;
        }

        return $ascii;
    }
}
