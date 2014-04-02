<?php


namespace BlueBear\EditorBundle\Tests;


class FakeItem
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
} 