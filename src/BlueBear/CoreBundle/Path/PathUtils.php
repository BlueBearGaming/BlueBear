<?php

namespace BlueBear\CoreBundle\Path;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Path\Converter\MapConverterInterface;
use BlueBear\CoreBundle\Utils\Position;
use Letournel\PathFinder\Algorithms\TravelingSalesman\NearestNeighbour;
use Letournel\PathFinder\Converters\Grid\ASCIISyntax;
use Letournel\PathFinder\Core\NodeGraph;

class PathUtils
{
    /**
     * @var MapConverterInterface
     */
    protected $converter;

    public function __construct(MapConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function getNearestNeighbour(Position $position, Map $map)
    {
        $asciiMap = $this
            ->converter
            ->convert($map);

        var_dump($asciiMap);

        $algorithm = new NearestNeighbour();
        $syntax = new ASCIISyntax();
        $grid = $syntax->convertToGrid($asciiMap);
        $matrix = $syntax->convertToMatrix($asciiMap);

        $nodeGraph = new NodeGraph($matrix);


        $source = $syntax->findAndCreateNode($asciiMap, ASCIISyntax::IN);
        $algorithm->setGraph($nodeGraph);
        $routes = $algorithm->computeRoute();


        var_dump($routes);





    }
}
