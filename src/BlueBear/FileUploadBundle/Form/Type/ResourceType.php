<?php

namespace BlueBear\FileUploadBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class ResourceType extends AbstractType
{
    protected $className = 'BlueBear\CoreBundle\Entity\Editor\Resource';
    protected $endpoint = 'resource';

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['endpoint'] = $this->endpoint;
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'resource';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->className,
            'query_builder' => $this->getQueryBuilder(),
        ]);
    }

    protected function getQueryBuilder()
    {
        return function(EntityRepository $repo) {
            return $repo->createQueryBuilder('e');
        };
    }
}
