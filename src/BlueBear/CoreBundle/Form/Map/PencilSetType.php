<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
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
            'choices' => Constant::getMapType(),
        ]);
        $builder->add('file', 'file', [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'help_block' => 'File to upload',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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