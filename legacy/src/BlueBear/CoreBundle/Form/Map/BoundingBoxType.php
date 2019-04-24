<?php


namespace App\Form\Map;

use App\Constant\Map\Constant;
use App\Entity\Behavior\SortEntity;
use App\Entity\Map\Pencil;
use App\Manager\Map\ImageManager;
use App\Manager\Map\LayerManager;
use App\Manager\Map\PencilSetManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BoundingBoxType extends AbstractType
{
    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'bounding_box';
    }
} 
