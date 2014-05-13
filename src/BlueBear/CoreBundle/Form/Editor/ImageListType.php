<?php


namespace BlueBear\CoreBundle\Form\Editor;

use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageListType extends AbstractType
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /**
         * @var Pencil|null $pencil
         */
        $pencil = array_key_exists('pencil', $options) ? $options['pencil'] : null;
        $images = $this->imageManager->findOrphans($pencil);
        $view->vars['images'] = $images;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'pencil' => null
        ]);
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