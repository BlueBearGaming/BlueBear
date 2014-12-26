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
        $layerTransformer = new EntityToChoiceTransformer();
        $layerTransformer->setManager($this->layerManager);
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
            'choices' => Constant::getPencilType(),
            'help_block' => 'Pencil type'
        ]);
        $builder->add(
            $builder->create('pencilSet', 'choice', [
                'choices' => $this->getSortedEntityForChoice($this->pencilSetManager->findAll()),
                'data' => $pencil->getPencilSet(),
                'help_block' => 'Pencil set which this pencil belongs'
            ])->addModelTransformer($pencilSetTransformer)
        );
        $builder->add(
            $builder->create(
                'allowedLayers', 'choice', [
                    'choices' => $this->getSortedEntityForChoice($this->layerManager->findAll()),
                    'data' => $pencil->getAllowedLayers(),
                    'multiple' => true,
                    'expanded' => true,
                    'help_block' => 'Allowed layers for this pencil (it means that the pencil can only be added in
                    those layers)'
                ]
            )->addModelTransformer($layerTransformer)
        );
        $builder->add(
            $builder
                ->create('image', 'image_list', [])
                ->addModelTransformer($imageTransformer)
        );
        $builder->add('imageX', 'integer', [
            'help_block' => 'Image x position'
        ]);
        $builder->add('imageY', 'integer', [
            'help_block' => 'Image y position',
        ]);
        $builder->add('width', 'integer', [
            'help_block' => 'Image width displayed (in px)',
        ]);
        $builder->add('height', 'integer', [
            'help_block' => 'Image height displayed (in px)',
        ]);
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