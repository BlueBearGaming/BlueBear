<?php

namespace BlueBear\GameBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\EngineBundle\Entity\EntityModelAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityModelAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('value', 'text');
        $builder->add('type', 'choice', [
            'choices' => Constant::getEntityModelAttributesTypes()
        ]);
        /** we need a event here, see https://github.com/symfony/symfony/issues/5694 **/
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) {
            /** @var EntityModelAttribute $attribute */
            $attribute = $formEvent->getData();
            $form = $formEvent->getForm();

            if ($attribute and $attribute->isDefault()) {
                $form->add('name', 'text', [
                    'read_only' => true,
                    'disabled' => 'disabled',
                ]);
            }
            $form->add('value', 'text');

            if ($attribute and $attribute->isDefault()) {
                $form->add('type', 'choice', [
                    'choices' => Constant::getEntityModelAttributesTypes(),
                    'attr' => [
                        'readonly' => true,
                        'disabled' => true,
                    ]
                ]);
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\EngineBundle\Entity\EntityModelAttribute'
        ]);
    }

    public function getName()
    {
        return 'entity_model_attribute';
    }
}
