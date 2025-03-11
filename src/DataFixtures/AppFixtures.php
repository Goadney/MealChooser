<?php

namespace App\DataFixtures;

use App\Entity\Saisons;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Ajout des saisons
        $saisons = ['HIVER', 'PRINTEMPS', 'ETE', 'AUTOMNE'];

        foreach ($saisons as $nom) {
            $saison = new Saisons();
            $saison->setName($nom);
            $manager->persist($saison);
        }

        $manager->flush();
    }
}
