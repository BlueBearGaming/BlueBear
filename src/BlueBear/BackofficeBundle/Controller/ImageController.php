<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Manager\ImageManager;
use BlueBear\CoreBundle\Manager\ResourceManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    use ControllerBehavior;

    /**
     * @Template()
     */
    public function indexAction()
    {
        $images = $this->getImageManager()->findAll();
        //$imagesWithoutPencil = $this->getImageManager()->findOrphans();

        return [
            'images' => $images
        ];
    }

    /**
     * @ParamConverter("image", class="BlueBear\CoreBundle\Entity\Editor\Image")
     * @Template()
     * @param Request $request
     * @param Image $image
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Image $image)
    {
        $form = $this->createForm('image', $image);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // save image object
            $this->getImageManager()->save($image);
            $file = $form->get('file')->getData();
            // upload new file
            if ($file) {
                // upload resource
                $this->getResourceManager()->upload($file, $image);
                $this->setMessage('Image has been successfully changed');
            }
            // redirect to image list
            return $this->redirect('@bluebear_backoffice_image');
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

    /**
     * Upload an image into backoffice
     *
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm('upload');
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            // upload file into resources directory, if it's a sprite it will vut into multiple images
            $this->getResourceManager()->upload($file);
            $this->setMessage('Image has been successfully uploaded');
            
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