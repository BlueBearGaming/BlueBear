<?php

namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name', TextType::class, [
            //'help_block' => 'Internal name of the the layer (eg: layer_0)'
        ]);
        $builder->add('label', TextType::class, [
            //'help_block' => 'Displayed name (eg: MyLabel)'
        ]);
        $builder->add('description', TextareaType::class, [
            //'help_block' => 'Layer description (eg: "What a beautiful layer !")'
        ]);
        $builder->add('index', IntegerType::class, [
            //'help_block' => 'Layer index (eg: 0,1,2... Works like css z-index)'
        ]);
        $builder->add('type', ChoiceType::class, [
            'choices' => array_flip(Constant::getLayerTypes()),
            //'help_block' => 'Layer type'
        ]);
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'layer';
    }
}
