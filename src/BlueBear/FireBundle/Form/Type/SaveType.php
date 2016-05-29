<?php

namespace BlueBear\FireBundle\Form\Type;

use BlueBear\CoreBundle\Entity\Game\Save\Save;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Save::class
            ]);
    }
}
