<?php

namespace App\Contracts\Map;

interface Positionable
{
    public function getX(): int;

    public function getY(): int;

    public function getZ(): int;
}
