<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Form\Editor\ImageToIdTransformer;
use BlueBear\CoreBundle\Manager\ImageManager;
use BlueBear\CoreBundle\Manager\LayerManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PencilType extends AbstractType
{
    /**
     * @var LayerManager
     */
    protected $layerManager;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('name');
        $builder->add('label');
        $builder->add('type', 'choice', [
            'choices' => Constant::getPencilType()
        ]);
        $builder->add('pencilSet', 'entity', [
            'class' => 'BlueBear\CoreBundle\Entity\Map\PencilSet',
            'property' => 'label',
            'expanded' => false,
            'multiple' => false
        ]);
        $builder->add(
            'allowedLayers', 'entity', [
            'class' => 'BlueBear\CoreBundle\Entity\Map\Layer',
            'property' => 'label',
            'expanded' => true,
            'multiple' => true
        ]);
        $builder->add(
            $builder
                ->create('image', 'image_list')
                ->addModelTransformer(new ImageToIdTransformer($this->imageManager))
        );
        $builder->add('imageX');
        $builder->add('imageY');
        $builder->add('width');
        $builder->add('height');
    }

    public function getName()
    {
        return 'pencil';
    }

    public function setLayerManager(LayerManager $layerManager)
    {
        $this->layerManager = $layerManager;
    }


    public function setImageManager(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }
} 