<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Behavior\SortEntity;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\ImageManager;
use BlueBear\CoreBundle\Manager\LayerManager;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PencilType extends AbstractType
{
    use SortEntity;
    /**
     * @var LayerManager
     */
    protected $layerManager;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var PencilSetManager $pencilSetManager
     */
    protected $pencilSetManager;

    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        /** @var Pencil $pencil */
        $pencil = $options['data'];
        // Transformers (yeh !)
        $pencilSetTransformer = new EntityToIdTransformer();
        $pencilSetTransformer->setManager($this->pencilSetManager);
        $imageTransformer = new EntityToIdTransformer();
        $imageTransformer->setManager($this->imageManager);

        $builder->add('name', 'text', [
            'help_block' => 'Internal name of the pencil (eg: pencil_0)'
        ]);
        $builder->add('label', 'text', [
            'help_block' => 'Displayed name (eg: MyPencil)'
        ]);
        $builder->add('description', 'textarea', [
            'help_block' => 'Pencil description'
        ]);
        $builder->add('type', 'choice', [
            'choices' => Constant::getLayerTypes(),
            'help_block' => 'Pencil type'
        ]);
        $builder->add(
            $builder->create('pencilSet', 'choice', [
                'choices' => $this->getSortedEntityForChoice($this->pencilSetManager->findAll()),
                'data' => $pencil->getPencilSet(),
                'help_block' => 'Pencil set which this pencil belongs'
            ])->addModelTransformer($pencilSetTransformer)
        );
        $builder->add('allowedLayerTypes', 'choice', [
            'choices' => Constant::getLayerTypes(),
            'data' => $pencil->getAllowedLayerTypes(),
            'multiple' => true,
            'expanded' => true,
            'horizontal_input_wrapper_class' => 'col-sm-9 form-inline-checkboxes',
        ]);
        $builder->add('imageX', 'number', [
            'help_block' => 'Image x position'
        ]);
        $builder->add('imageY', 'number', [
            'help_block' => 'Image y position',
        ]);
        $builder->add('width', 'number', [
            'data' => 1, // by default, image take 1 tile
            'help_block' => 'Image width in tiles ("1" means that image width take 1 x Map.CellSize)',
        ]);
        $builder->add('height', 'number', [
            'data' => 1, // by default, image take 1 tile
            'help_block' => 'Image height in tiles ("1" means that image height take 1 x Map.CellSize)',
        ]);
        $builder->add('image', 'resource_image');
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

    /**
     * @param PencilSetManager $pencilSetManager
     */
    public function setPencilSetManager(PencilSetManager $pencilSetManager)
    {
        $this->pencilSetManager = $pencilSetManager;
    }
} 