<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Manuscript;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ManuscriptFixtures extends Fixture implements DependentFixtureInterface
{

    
    public function load(ObjectManager $manager): void
    {
        $manuscript = new Manuscript();
        $manuscript->setTitle('Fixtures');
        $manuscript->setContent('Je suis une fixture !');
        $manuscript->setImageName('st.1');
        $manuscript->setAuthorId($this->getReference(UserFixtures::USER_REFERENCE));
        $manuscript->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($manuscript);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
