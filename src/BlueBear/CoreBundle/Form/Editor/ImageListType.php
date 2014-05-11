<?php


namespace BlueBear\CoreBundle\Form\Editor;

use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ImageListType extends AbstractType
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $images = $this->imageManager->findOrphans();
        $view->vars['images'] = $images;
    }

    public function setImageManager(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function getName()
    {
        return 'image_list';
    }

    public function getParent()
    {
        return 'hidden';
    }
}