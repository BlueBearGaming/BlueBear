<?php

namespace App\Form\Engine;

use BlueBear\EngineBundle\Engine\Engine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EngineEventTestType extends AbstractType
{
    /**
     * @var Engine
     */
    protected $engine;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('event', 'choice', [
            'choices' => $this->getEvents(),
            'help_block' => 'Event to call'
        ]);
        $builder->add('data', 'textarea', [
            'help_block' => 'Event data in json (eg: {' . "\n"
                . ' {"mapName": "test", "x": 4, "y": 2}
                }'
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
        return 'engine_event_test';
    }

    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
    }

    protected function getEvents()
    {
        $events = $this->engine->getAllowedEvents();
        $sorted = [];

        foreach ($events as $event) {
            $sorted[$event] = $event;
        }
        return $sorted;
    }
} 
