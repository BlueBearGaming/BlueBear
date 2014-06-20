<?php


namespace BlueBear\CoreBundle\Form\Map;

use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Manager\PencilManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PencilSetListType extends AbstractType
{
    /**
     * @var PencilManager $pencilManager
     */
    protected $pencilManager;

    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('pencils', 'collection', [
            'type' => 'pencil_set'
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $parent = $form->getParent()->getData();
        $view->vars['list'] = $this->pencilManager->findAll();

        if ($parent instanceof Map) {
            $view->vars['map'] = $parent;
        }
    }

    public function setPencilManager(PencilManager $pencilManager)
    {
        $this->pencilManager = $pencilManager;
    }

    public function getName()
    {
        return 'pencil_set_list';
    }

    public function getParent()
    {
        return 'collection';
    }
} 