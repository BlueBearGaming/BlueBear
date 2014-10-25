<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Form\Editor\ImageToIdTransformer;
use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name', 'text', [
            'help_block' => 'Internal map name (eg: map_0)'
        ]);
        $builder->add('label', 'text', [
            'help_block' => 'Displayed map name (eg: My first Map)'
        ]);
        $builder->add('width', 'integer', [
            'help_block' => 'Number of map columns'
        ]);
        $builder->add('height', 'text', [
            'help_block' => 'Number of map rows'
        ]);
        $builder->add('type', 'choice', [
            'choices' => Constant::getMapType(),
            'help_block' => 'Map type'
        ]);
        $builder->add('pencilSets', 'pencil_set_list');
    }

    public function getName()
    {
        return 'map';
    }
}