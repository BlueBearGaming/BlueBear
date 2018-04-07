<?php

namespace BlueBear\BackofficeBundle\Form\Handler;


use BlueBear\CoreBundle\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormHandler
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * LoginFormHandler constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface       $entityManager
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     *
     * @throws Exception
     */
    public function handle(FormInterface $form)
    {
        $user = $form->getData();

        if (!($user instanceof User)) {
            throw new Exception('Only user from class '.User::class.' are handled');
        }
        $user->setUsernameCanonical($user->getUsername());
        $user->setEmailCanonical($user->getEmail());
        $user->setSalt($this->createSalt());
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @return string
     */
    protected function createSalt()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}
