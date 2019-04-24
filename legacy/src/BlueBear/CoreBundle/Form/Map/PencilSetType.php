<?php


namespace App\Form\Map;

use App\Constant\Map\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PencilSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('label');
        $builder->add('type', 'choice', [
            'choices' => Constant::getMapTypes(),
        ]);
        $builder->add('sprite', 'resource_image');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Map\PencilSet'
        ]);
    }

    public function getName()
    {
        return 'pencil_set';
    }
} 
