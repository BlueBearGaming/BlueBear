<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Form\Editor\ImageToIdTransformer;
use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('label');
        $builder->add('width');
        $builder->add('height');
        $builder->add('type', 'choice', [
            'choices' => Constant::getMapType()
        ]);
        $builder->add('pencilSets', 'pencil_set_list', [
        ]);
    }

    public function getName()
    {
        return 'map';
    }
}