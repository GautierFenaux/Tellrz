<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $question = new Question();
        $question = $question->setContent('Avez-vous aimé le livre ?');
        $manager->persist($question);
        
        $question = new Question();
        $question = $question->setContent('Que pensez-vous du personnage principal ?');
        $manager->persist($question);

        $manager->flush();
    }
}
