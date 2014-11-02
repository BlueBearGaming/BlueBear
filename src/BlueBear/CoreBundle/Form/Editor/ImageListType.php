<?php


namespace BlueBear\CoreBundle\Form\Editor;

use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ImageListType extends AbstractType
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $orphans = $this->imageManager->findOrphans();
        $view->vars['orphans'] = $orphans;
    }

    public function setImageManager(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'image_list';
    }
}