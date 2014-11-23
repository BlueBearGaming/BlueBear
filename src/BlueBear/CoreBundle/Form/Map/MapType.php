<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Manager\LayerManager;
use BlueBear\CoreBundle\Manager\PencilSetManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

        $builder->add('name', 'text', [
            'help_block' => 'Internal map name (eg: map_0)'
        ]);
        $builder->add('label', 'text', [
            'help_block' => 'Displayed map name (eg: My first Map)'
        ]);
        $builder->add('width', 'integer', [
            'help_block' => 'Number of map columns'
        ]);
        $builder->add('height', 'integer', [
            'help_block' => 'Number of map rows'
        ]);
        $builder->add('type', 'choice', [
            'choices' => Constant::getMapType(),
            'help_block' => 'Map type'
        ]);
        $builder->add(
            $builder->create(
                'pencilSets', 'choice', [
                    'choices' => $this->getSortedPencilSets($this->pencilSetManager->findAll()),
                    'data' => $map->getPencilSets(),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )->addModelTransformer($transformer)
        );
        $builder->add(
            $builder->create(
                'layers', 'choice', [
                    'choices' => $this->getSortedLayers($this->layerManager->findAll()),
                    'data' => $map->getLayers(),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )->addModelTransformer($layerTransformer)
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\CoreBundle\Entity\Map\Map'
        ]);
    }

    public function getName()
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

    public function setLayerManager(LayerManager $layerManager)
    {
        $this->layerManager = $layerManager;
    }

    protected function getSortedPencilSets($pencilsSets)
    {
        $sorted = [];

        /** @var PencilSet $pencilsSet */
        foreach ($pencilsSets as $pencilsSet) {
            $sorted[$pencilsSet->getId()] = $pencilsSet->getLabel() . ' ("' . $pencilsSet->getName() . '")';
        }
        return $sorted;
    }

    protected function getSortedLayers($layers)
    {
        $sorted = [];

        /** @var Layer $layer */
        foreach ($layers as $layer) {
            $sorted[$layer->getId()] = $layer->getLabel() . ' ("' . $layer->getName() . '")';
        }
        return $sorted;
    }
}