<?php

namespace BlueBear\WorldBrowserBundle\Model;

use BlueBear\WorldBrowserBundle\Noise\PerlinNoiseGenerator;

class Map
{
    const MAP_SIZE = 64; // Changing this will completely transform the computed heightmap

    /** @var Cell[][] */
    protected $cells;

    /** @var array */
    protected $heightMap;

    /**
     * @param string $seed
     */
    public function __construct(string $seed)
    {
        $generator = new PerlinNoiseGenerator();
        $generator->setSize(self::MAP_SIZE);
        $generator->setPersistence(0.9);
        $generator->setMapSeed($seed);
        $this->heightMap = $generator->generate();

        $maxY = count($this->heightMap) / 2;
        foreach ($this->heightMap as $x => $heights) {
            foreach ($heights as $y => $rawHeight) {
                if ($y > $maxY) {
                    break;
                }
                $height = round(($rawHeight - 1) * 255 - 40);
                $offset = ($x % 2) ? 1 : 0;
                $axialY = (int) floor($y + ($x + $offset) / 2 - $x);
                $cell = new Cell($this, $x, $axialY);
                $cell->setHeight($height);

                $this->cells[$cell->getX()][$cell->getY()] = $cell;
            }
        }
    }

    /**
     * @return Cell[][]
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return Cell
     */
    public function getCell($x, $y)
    {
        if (!isset($this->cells[$x][$y])) {
            return null;
        }

        return $this->cells[$x][$y];
    }

    /**
     * @return array
     */
    public function getHeightMap()
    {
        return $this->heightMap;
    }
}
