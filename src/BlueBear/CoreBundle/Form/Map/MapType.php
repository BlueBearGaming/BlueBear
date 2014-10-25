<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
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

    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
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
        $builder->add('pencilSets', 'choice', [
            'choices' => $this->getSortedPencilSets(),
            'multiple' => true,
            'expanded' => true
        ]);

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
    public function setPencilSetManager($pencilSetManager)
    {
        $this->pencilSetManager = $pencilSetManager;
    }

    protected function getSortedPencilSets()
    {
        $pencilsSets = $this->pencilSetManager->findAll();
        $sorted = [];

        /** @var PencilSet $pencilsSet */
        foreach ($pencilsSets as $pencilsSet) {
            $sorted[$pencilsSet->getName()] = $pencilsSet->getLabel() . ' ("' . $pencilsSet->getName() . '")';
        }
        return $sorted;
    }
}