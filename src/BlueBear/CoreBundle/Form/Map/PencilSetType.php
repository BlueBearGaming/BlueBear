<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PencilSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('label');
        $builder->add('type', ChoiceType::class, [
            'choices' => array_flip(Constant::getMapTypes()),
        ]);
        //$builder->add('sprite', ResourceIma);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\CoreBundle\Entity\Map\PencilSet'
        ]);
    }

    public function getName()
    {
        return 'pencil_set';
    }
} 
