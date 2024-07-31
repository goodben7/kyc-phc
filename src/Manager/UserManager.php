<?php

namespace App\Manager;

use App\Entity\User;
use App\Model\NewUserModel;
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

    public function createFrom(NewUserModel $model): User {

        $user = new User();

        $user->setPhone($model->phone);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setPassword($this->hasher->hashPassword($user, $model->plainPassword));
        $user->setCode($model->plainPassword);
        $user->setDisplayName($model->displayName);
        $user->setRoles([$model->roles]);

        $this->em->persist($user);

        $user->setUsername($user->getPhone());

        $this->em->persist($user);
        $this->em->flush();
        
        return $user;
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