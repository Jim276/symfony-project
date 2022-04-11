<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordHasherInterface */

    private $hasher;
    public const USER_REFERENCE = 'user';

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $i = 1;
        foreach(['ROLE_ADMIN','ROLE_MANAGER','ROLE_USER'] as $role
        ){
            $user = new User();
            $user->setEmail("user_$i@formation.com");
            $user->setFullname("Utilisateur $role");
            $user->setRoles([$role]);
            $user->setPassword($this->hasher->hashPassword($user,'password'));
            $manager->persist($user);
            $i++;
        }
        $manager->flush();
        $this->addReference(self::USER_REFERENCE,$user);
    }
}