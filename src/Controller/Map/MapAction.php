<?php

namespace App\Controller\Map;

use App\Engine\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MapAction
{
    /**
     * @var EngineInterface
     */
    private $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function __invoke(Request $request): Response
    {
        return $this->engine->run($request);
    }
}
