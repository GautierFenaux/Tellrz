<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture 
{

    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Gautier');
        $user->setName('Fenaux');
        $user->setPassword('pass123');
        $user->setRoles(['ROLE_USER']);
        $user->setEmail('gautier.fenaux@gmail.com');
        $user->setImageName('test.jpg');
        $user->setIsVerified(true);
        $user->setUpdatedAt(new DateTimeImmutable());
        $this->addReference(self::USER_REFERENCE, $user);

        $manager->persist($user);

        $manager->flush();
    }
}
