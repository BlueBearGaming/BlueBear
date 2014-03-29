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
            $directory = realpath(__DIR__ . '/../../../../web/uploads/tmp') . '/';
            $destinationDirectory = realpath(__DIR__ . '/../../../../web/uploads/sprites') . '/';
            $file->move($directory, $file->getClientOriginalName());
            $splitter = new SpriteSplitter();
            $splitter->split($directory.$file->getClientOriginalName(), $destinationDirectory);
            die('valid');
        }
        return $this->render('BlueBearBackofficeBundle:Image:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
} 