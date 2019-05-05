<?php

namespace App\Engine;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EngineInterface
{
    public function run(Request $request): Response;
} 
