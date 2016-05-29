<?php

namespace BlueBear\BackofficeBundle\Controller;

use BlueBear\BackofficeBundle\Form\Type\UserType;
use BlueBear\CoreBundle\Entity\User\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Template()
     * @param Request $request
     * @return array
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

        return [
            'form' => $form->createView()
        ];
    }
}
