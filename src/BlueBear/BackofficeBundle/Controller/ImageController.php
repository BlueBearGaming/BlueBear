<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Manager\ImageManager;
use BlueBear\CoreBundle\Manager\ResourceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $imagesWithoutPencil = $this->getImageManager()->findOrphans();

        return [];
    }

    /**
     * @ParamConverter("image", class="BlueBear\CoreBundle\Entity\Editor\Image")
     * @Template()
     * @param Request $request
     * @param Image $image
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, Image $image)
    {
        $form = $this->createForm('image', $image);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getImageManager()->save($image);
            $this->setMessage('Image has been successfully changed');
            return $this->redirect('@bluebear_backoffice_image_orphans');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     */
    public function listOrphansAction()
    {
        // find all orphan images
        $images = $this->getImageManager()->findOrphans();

        return [
            'images' => $images
        ];
    }

    public function uploadAction(Request $request)
    {
        $form = $this->createForm('upload');
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $uploadType = $form->get('type')->getData();
            // upload file into resources directory, if it's a sprite it will vut into multiple images
            $this->getResourceManager()->upload($file, $uploadType);

            $this->setMessage('Image has been successfully uploaded');

            if ($uploadType == Image::IMAGE_TYPE_RPG_MAKER_SPRITE) {
                $this->setMessage('Sprite has been successfully cut');
            }
            return $this->redirect('@bluebear_backoffice_image');
        }
        return $this->render('BlueBearBackofficeBundle:Image:upload.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return ResourceManager
     */
    protected function getResourceManager()
    {
        return $this->get('bluebear.manager.resource');
    }

    /**
     * @return ImageManager
     */
    protected function getImageManager()
    {
        return $this->get('bluebear.manager.image');
    }
} 