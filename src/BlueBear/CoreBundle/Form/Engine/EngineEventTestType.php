<?php

namespace BlueBear\CoreBundle\Form\Engine;

use BlueBear\EngineBundle\Event\EngineEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EngineEventTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('event', 'choice', [
            'choices' => $this->getEvents(),
            'help_block' => 'Event to call'
        ]);
        $builder->add('data', 'textarea', [
            'help_block' => 'Event data in json (eg: {' . "\n"
                . ' { x: 4, y: 2}
                }'
        ]);
    }

    /**
     * {@inheritdoc}
     */
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

    protected function getEvents()
    {
        $events = EngineEvent::getAllowedEvents();
        $sorted = [];

        foreach ($events as $event) {
            $sorted[$event] = $event;
        }
        return $sorted;
    }
} 