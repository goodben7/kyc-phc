<?php
namespace App\Doctrine;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;

class IdGenerator extends AbstractIdGenerator 
{
    public function generateId(EntityManagerInterface $em, object|null $entity): mixed
    {
        $currentDateTime = new \DateTime();
        $dateTimeString = $currentDateTime->format('YmdHis');
        return $entity::ID_PREFIX . strtoupper($dateTimeString);
    }
}