<?php

namespace BlueBear\GameBundle\Form;

use Symfony\Component\Form\AbstractType;

class AttributeCollectionType extends AbstractType
{
    public function getName()
    {
        return 'attribute_collection';
    }

    public function getParent()
    {
        return 'collection';
    }
}