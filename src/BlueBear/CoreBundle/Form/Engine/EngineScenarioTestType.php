<?php

namespace BlueBear\CoreBundle\Form\Engine;

use BlueBear\CoreBundle\Entity\Map\Map;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EngineScenarioTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['step'] == 1) {
            $builder->add('maps', 'choice', [
                'choices' => $this->getMapChoices($options['maps'])
            ]);
            $builder->add('x', 'integer');
            $builder->add('y', 'integer');
            $builder->add('scenario', 'hidden', [
                'data' => 'bluebear.editor.putEntity'
            ]);
        } else if ($options['step'] == 2) {
            // first step read only
            $builder->add('maps', 'choice', [
                'choices' => $this->getMapChoices($options['maps']),
                'read_only' => true
            ]);
            $builder->add('x', 'integer', [
                'read_only' => true
            ]);
            $builder->add('y', 'integer', [
                'read_only' => true
            ]);
        }
        $builder->add('step', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'maps' => []
        ]);
        $resolver->setRequired([
            'step'
        ]);
    }


    public function getName()
    {
        return 'engine_scenario_test';
    }

    protected function getMapChoices($maps)
    {
        $choices = [];
        /** @var Map $map */
        foreach ($maps as $map) {
            $choices[$map->getId()] = $map->getLabel();
        }
        return $choices;
    }
}
