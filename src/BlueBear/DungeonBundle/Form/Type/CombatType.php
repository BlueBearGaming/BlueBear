<?php

namespace BlueBear\DungeonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CombatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fighter_1', 'choice', [
                'choices' => $options['entities']
            ])->add('player_1', 'choice', [
                'choices' => $options['players']
            ])->add('fighter_2', 'choice', [
                'choices' => $options['entities']
            ])->add('player_2', 'choice', [
                'choices' => $options['players']
            ]);
        // TODO add validator to not have the same player and the unit twice
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'entities',
            'players'
        ]);
        $resolver->setDefaults([
            'constraints' => new Callback(array('callback' => function ($value, ExecutionContextInterface $context) {
                if ($value['fighter_1'] == $value['fighter_2']) {
                    $context->addViolationAt('fighter_1', 'Fighter should be different');
                    $context->addViolationAt('fighter_2', 'Fighter should be different');
                }
                if ($value['player_1'] == $value['player_2']) {
                    $context->addViolationAt('player_1', 'Player should be different');
                    $context->addViolationAt('player_2', 'Player should be different');
                }
            }))
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'combat';
    }
}
