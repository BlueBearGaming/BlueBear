<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Manager\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ImageController extends Controller
{
    use ControllerBehavior;

    public function indexAction()
    {
        $form = $this->createForm('sprite');

        return $this->render('BlueBearBackofficeBundle:Image:index.html.twig', [
            'form' => $form->createView()
        ]);
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
        $form = $this->createForm('sprite');
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->getImageManager()->splitSprite($file);
            // inform user
            $this->setMessage('Sprite has been successfully split');
            return $this->redirect('@bluebear_backoffice_image_orphans');
        }
        return $this->render('BlueBearBackofficeBundle:Image:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return ImageManager
     */
    protected function getImageManager()
    {
        return $this->get('bluebear.manager.image');
    }
} 