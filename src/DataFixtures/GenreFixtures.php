<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $genre = new Genre();
        $genre->setgenreName('Horreur');
        $manager->persist($genre);


        $genre = new Genre();
        $genre->setgenreName('Aventure');
        $manager->persist($genre);

        $genre = new Genre();
        $genre->setgenreName('Science-fiction');
        $manager->persist($genre);


        $genre = new Genre();
        $genre->setgenreName('Utopie' );
        $manager->persist($genre);

        $genre = new Genre(); 
        $genre->setgenreName('Historique');
        $manager->persist($genre);


        $genre = new Genre();
        $genre->setgenreName('Fantastique');
        $manager->persist($genre);

        $genre = new Genre();
        $genre->setgenreName('Poésie');
        $manager->persist($genre);


        $genre = new Genre();
        $genre->setgenreName('Action');
        $manager->persist($genre);

        $genre = new Genre();
        $genre->setgenreName('Nouvelle');
        $manager->persist($genre);


        $genre = new Genre();
        $genre->setgenreName('Classique');
        $manager->persist($genre);

        
        $manager->flush();
    }
}
