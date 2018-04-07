<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Form\Type\UserType;
use BlueBear\CoreBundle\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'add_roles_choices' => true
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this
                ->get('bluebear.form.login_handler')
                ->handle($form);

            return $this->redirectToRoute('bluebear.homepage');
        }

        return $this->render('@BlueBearBackoffice/User/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
