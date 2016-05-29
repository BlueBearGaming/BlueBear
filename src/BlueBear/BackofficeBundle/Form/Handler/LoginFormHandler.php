<?php

namespace BlueBear\BackofficeBundle\Form\Handler;


use BlueBear\CoreBundle\Entity\User\User;
use BlueBear\CoreBundle\Entity\User\UserRepository;
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
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

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

        $this
            ->userRepository
            ->save($user);
    }

    /**
     * @return string
     */
    protected function createSalt()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}
