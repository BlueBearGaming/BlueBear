<?php

namespace App\Controller\Admin;

use App\Manager\Map\MapManager;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Homepage
{
    /**
     * @var MapManager
     */
    private $manager;

    /**
     * @var Environment
     */
    private $environment;

    public function __construct(MapManager $manager, Environment $environment)
    {
        $this->manager = $manager;
        $this->environment = $environment;
    }

    public function __invoke(): Response
    {
        return new Response($this->environment->render(':admin:pages:homepage.html.twig'));
    }
}
