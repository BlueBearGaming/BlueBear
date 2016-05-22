<?php

namespace BlueBear\CoreBundle\Path\Converter;

use BlueBear\CoreBundle\Entity\Map\Map;

interface MapConverterInterface
{
    public function convert(Map $map);
}
