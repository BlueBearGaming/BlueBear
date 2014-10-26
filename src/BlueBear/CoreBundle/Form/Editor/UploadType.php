<?php


namespace BlueBear\CoreBundle\Form\Editor;

use BlueBear\CoreBundle\Entity\Editor\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('type', 'choice', [
            'choices' => [
                Image::IMAGE_TYPE_SINGLE_IMAGE => 'Single image',
                Image::IMAGE_TYPE_RPG_MAKER_SPRITE => 'RPG Maker sprite'
            ],
            'expanded' => true,
            'data' => Image::IMAGE_TYPE_SINGLE_IMAGE,
            'help_block' => 'Upload a single or a RPG-Maker sprite'
        ]);
        $builder->add('file', 'file', [
            'label' => 'Upload a sprite',
            'help_block' => 'File to upload'
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
        return 'upload';
    }
} 