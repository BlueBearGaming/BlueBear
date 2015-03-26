<?php

namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Behavior\SortEntity;
use BlueBear\CoreBundle\Entity\Editor\ImageRepository;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PencilType extends AbstractType
{
    use SortEntity;

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
        $image = null;
        if ($builder->getData()) {
            $image = $builder->getData()->getImage();
        }

        $builder->add('name', 'text', [
            'help_block' => 'Internal name of the pencil (eg: pencil_0)'
        ]);
        $builder->add('label', 'text', [
            'help_block' => 'Displayed name (eg: MyPencil)'
        ]);
        $builder->add('description', 'textarea', [
            'help_block' => 'Pencil description',
            'required' => false
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
            'help_block' => 'Image width in tiles ("1" means that image width take 1 x Map.CellSize)',
        ]);
        $builder->add('height', 'number', [
            'help_block' => 'Image height in tiles ("1" means that image height take 1 x Map.CellSize)',
        ]);
        $builder->add('image', 'resource_image', [
            'query_builder' => function(ImageRepository $repo) use ($image) {
                return $repo->getQbForOrphans($image);
            },
        ]);
        //$builder->add('boundingBox', new BoundingBoxType()); // @wip
    }

    public function getName()
    {
        return 'pencil';
    }

    /**
     * @param PencilSetManager $pencilSetManager
     */
    public function setPencilSetManager(PencilSetManager $pencilSetManager)
    {
        $this->pencilSetManager = $pencilSetManager;
    }
} 