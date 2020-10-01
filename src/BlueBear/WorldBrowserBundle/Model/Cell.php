<?php

namespace BlueBear\WorldBrowserBundle\Model;

class Cell
{
    /** @var Map */
    protected $map;

    /** @var int */
    protected $x;

    /** @var int */
    protected $y;

    /** @var int */
    protected $height;

    /**
     * Cell constructor.
     *
     * @param Map $map
     * @param int $x
     * @param int $y
     */
    public function __construct(Map $map, $x, $y)
    {
        $this->map = $map;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = (int) $height;
    }

    /**
     * @param Cell $cell
     *
     * @return int
     */
    public function distance(Cell $cell)
    {
        return (abs($this->getY() - $cell->getY())
                + abs($this->getY() + $this->getX() - $cell->getY() - $cell->getX())
                + abs($this->getX() - $cell->getX())) / 2;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        $height = $this->getHeight();
        if ($height < 0) { // Ocean / lake
            $color = [
                'H' => 240,
                'S' => 62,
                'L' => 50 + $height / 2,
            ];
        } elseif ($height < 10) { // Swamp / beach
            $color = [
                'H' => 30,
                'S' => 100,
                'L' => 70 + $height * 2,
            ];
        } elseif ($height < 90) { // Forest
            $color = [
                'H' => 100,
                'S' => 100,
                'L' => 30 - $height / 4,
            ];
        } elseif ($height < 120) { // Mountains / grass
            $color = [
                'H' => 100,
                'S' => 90 - $height * 1.5 + 90,
                'L' => 10 + $height - 90,
            ];
        } elseif ($height < 150) { // Rocks
            $color = [
                'H' => 100,
                'S' => max(20 - $height + 130, 0),
                'L' => 30 + $height - 130,
            ];
        } else { // Snow
            $color = [
                'H' => 100,
                'S' => 0,
                'L' => 90,
            ];
        }

        return "hsl({$color['H']}, {$color['S']}%, {$color['L']}%)";
    }

    /**
     * @return string
     */
    public function getPencilName(): string
    {
        $height = $this->getHeight();
        if ($height < 0) { // Ocean / lake
            return 'tilewater';
        }
        if ($height < 10) { // Swamp / beach
            return 'tilesand';
        }
        if ($height < 90) { // Forest
            return 'tilegrass';
        }
        if ($height < 160) { // Mountains / grass
            return 'tileautumn';
        }
        if ($height < 180) { // Rocks
            return 'tilestone';
        }

        return 'tilesnow';
    }
}
