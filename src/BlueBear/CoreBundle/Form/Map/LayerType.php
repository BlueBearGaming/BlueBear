<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
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
        $builder->add('type', 'choice', [
            'choices' => Constant::getLayerType(),
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