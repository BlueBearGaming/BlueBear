<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Manager\ImageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $images = $this->getImageManager()->findAll();

        return [
            'images' => $images
        ];
    }

    /**
     * @return ImageManager
     */
    protected function getImageManager()
    {
        return $this->get('bluebear.manager.image');
    }
} 