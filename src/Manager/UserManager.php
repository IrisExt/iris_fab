<?php

namespace App\Manager;

use FOS\UserBundle\Model\UserManager as BaseUserManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;

class UserManager extends BaseUserManager
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(UserInterface $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function deleteUser($email)
    {
        return parent::deleteUser();
    }

    public function findUserBy($email)
    {
        return parent::findUserBy();
    }

    public function findUsers()
    {
        return parent::findUsers();
    }

    public function getClass()
    {
        return parent::getClass();
    }

    public function reloadUser($email)
    {
        return parent::reloadUser($email);
    }

    public function updateUser($email)
    {
        return parent::updateUser($email);
    }
}
