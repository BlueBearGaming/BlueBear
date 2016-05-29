<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Form\Type\LoginType;
use BlueBear\BaseBundle\Behavior\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class GuardController extends Controller
{
    use ControllerTrait;

    public function loginAction()
    {
        $user = $this->getUser();

        // already authenticated
        if ($user instanceof UserInterface) {
            return $this->redirectToRoute('bluebear.homepage');
        }
        $exception = $this
            ->get('security.authentication_utils')
            ->getLastAuthenticationError();
        $error = $exception ? $exception->getMessage() : null;
        $form = $this->createForm(LoginType::class);

        return $this->render('@BlueBearBackoffice/Guard/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    public function loginCheckAction()
    {
        // will never be executed
        return new Response();
    }
}
