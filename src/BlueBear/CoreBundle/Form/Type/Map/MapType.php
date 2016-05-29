<?php


namespace BlueBear\CoreBundle\Form\Type\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Form\Map\EntityToChoiceTransformer;
use BlueBear\CoreBundle\Manager\LayerManager;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MapType extends AbstractType
{
    /**
     * @var PencilSetManager $pencilSetManager
     */
    protected $pencilSetManager;

    /**
     * @var LayerManager $layerManager
     */
    protected $layerManager;

    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        /** @var Map $map */
        $map = $options['data'];
        $transformer = new EntityToChoiceTransformer();
        $transformer->setManager($this->pencilSetManager);
        $layerTransformer = new EntityToChoiceTransformer();
        $layerTransformer->setManager($this->layerManager);
        $layers = $this->layerManager->findAll();

        $builder->add('name', TextType::class, [
            //'help_block' => 'Internal map name (eg: map_0)'
        ]);
        $builder->add('label', TextType::class, [
            //'help_block' => 'Displayed map name (eg: My first Map)'
        ]);
        $builder->add('type', ChoiceType::class, [
            'choices' => Constant::getMapTypes(),
            //'help_block' => 'Map type'
        ]);
        $builder->add('cellSize', ChoiceType::class, [
            'choices' => [
                '64' => '64px',
                '128' => '128px',
            ],
            //'help_block' => 'Cell size (in px)'
        ]);
        $builder->add('startX', IntegerType::class, [
            //'help_block' => 'Starting x position'
        ]);
        $builder->add('startY', IntegerType::class, [
            //'help_block' => 'Starting y position'
        ]);
        $builder->add(
            $builder->create(
                'pencilSets', ChoiceType::class, [
                    'choices' => $this->getSortedPencilSets($this->pencilSetManager->findAll()),
                    'data' => $map->getPencilSets(),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )->addModelTransformer($transformer)
        );
        $builder->add(
            $builder->create(
                'layers', ChoiceType::class, [
                    'choices' => $this->getSortedLayers($layers),
                    'data' => $layers,
                    'multiple' => true,
                    'expanded' => true,
                ]
            )->addModelTransformer($layerTransformer)
        );
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\CoreBundle\Entity\Map\Map'
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'map';
    }

    /**
     * @param PencilSetManager $pencilSetManager
     */
    public function setPencilSetManager(PencilSetManager $pencilSetManager)
    {
        $this->pencilSetManager = $pencilSetManager;
    }

    /**
     * @param LayerManager $layerManager
     */
    public function setLayerManager(LayerManager $layerManager)
    {
        $this->layerManager = $layerManager;
    }

    /**
     * @param $pencilsSets
     * @return array
     */
    protected function getSortedPencilSets($pencilsSets)
    {
        $sorted = [];

        /** @var PencilSet $pencilsSet */
        foreach ($pencilsSets as $pencilsSet) {
            $sorted[$pencilsSet->getLabel() . ' ("' . $pencilsSet->getName() . '")'] = $pencilsSet->getId();
        }
        return $sorted;
    }

    /**
     * @param $layers
     * @return array
     */
    protected function getSortedLayers($layers)
    {
        $sorted = [];

        /** @var Layer $layer */
        foreach ($layers as $layer) {
            $sorted[$layer->getLabel() . ' ("' . $layer->getName() . '")'] = $layer->getId();
        }
        return $sorted;
    }
}
