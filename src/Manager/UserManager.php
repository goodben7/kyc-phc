<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    public function __construct(
        private EntityManagerInterface $em, 
        private UserPasswordHasherInterface $hasher
    )
    {
    }
    public function create(User $user): User 
    {

        if ($user->getPlainPassword()) {
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
            $user->eraseCredentials();
        }

        $user->setCreatedAt(new \DateTimeImmutable('now'));

        $this->em->persist($user);

        $user->setUsername($user->getPhone());

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

}