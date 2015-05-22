<?php

namespace BlueBear\EngineBundle\Form;

use BlueBear\CoreBundle\Constant\Map\Constant;
use BlueBear\EngineBundle\Entity\EntityInstanceAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityInstanceAttributeType extends AbstractType
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
            /** @var EntityInstanceAttribute $attribute */
            $attribute = $formEvent->getData();
            $form = $formEvent->getForm();

            if ($attribute and $attribute->isDefault()) {
                $form->add('name', 'text', [
                    'attr' => [
                        'readonly' => true
                    ]
                ]);
                $form->add('value', 'text');
                $form->add('type', 'choice', [
                    'choices' => Constant::getEntityModelAttributesTypes(),
                    'attr' => [
                        'readonly' => true
                    ]
                ]);
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BlueBear\EngineBundle\Entity\EntityInstanceAttribute'
        ]);
    }

    public function getName()
    {
        return 'entity_instance_attribute';
    }
}
