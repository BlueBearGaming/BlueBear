<?php


namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Controller\Behavior\ControllerBehavior;
use BlueBear\BackofficeBundle\Utils\Sprite\SpriteSplitter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


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

    public function editAction()
    {

    }

    public function uploadAction(Request $request)
    {
        $form = $this->createForm('sprite');
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->get('bluebear.manager.image')->splitSprite($file);
            // inform user
            $this->setMessage('Sprite has been successfully split');
            return $this->redirect('@blue_bear_backoffice_uncompleted_item_list');
        }
        return $this->render('BlueBearBackofficeBundle:Image:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
} 