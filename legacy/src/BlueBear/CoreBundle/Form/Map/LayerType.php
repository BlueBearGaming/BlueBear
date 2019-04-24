<?php

namespace App\Form\Map;

use App\Constant\Map\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name', 'text', [
            'help_block' => 'Internal name of the the layer (eg: layer_0)'
        ]);
        $builder->add('label', 'text', [
            'help_block' => 'Displayed name (eg: MyLabel)'
        ]);
        $builder->add('description', 'textarea', [
            'help_block' => 'Layer description (eg: "What a beautiful layer !")'
        ]);
        $builder->add('index', 'integer', [
            'help_block' => 'Layer index (eg: 0,1,2... Works like css z-index)'
        ]);
        $builder->add('type', 'choice', [
            'choices' => Constant::getLayerTypes(),
            'help_block' => 'Layer type'
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'label' => false
        ]);
    }

    public function getName()
    {
        return 'layer';
    }
}
