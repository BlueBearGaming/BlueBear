<?php


namespace BlueBear\EditorBundle\Tests;


class FakePencil
{
    public function getId()
    {
        return rand(1, 1000);
    }

    public function getName()
    {
        $names = ['object_1', 'land_1', 'unit_1'];

        return $names[rand(0, count($names) - 1)];
    }

    public function getDisplayName()
    {
        $names = ['What a beautiful object !', 'Oh! What a land!', 'Unit is Good'];

        return $names[rand(0, count($names) - 1)];
    }

    public function getLayer()
    {
        $names = ['Layer 1', 'Layer 2', 'Layer 3'];

        return $names[rand(0, count($names) - 1)];
    }

    public function getType()
    {
        $names = ['Layer 1', 'Layer 2', 'Layer 3'];

        return $names[rand(0, count($names) - 1)];
    }

    public function getImages()
    {

    }

    public function getTags()
    {
        return $this->tags;
    }
} 