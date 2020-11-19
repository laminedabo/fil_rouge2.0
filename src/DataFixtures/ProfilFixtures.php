<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    const ref = ['ADMIN', 'CM', 'FORMATEUR', 'APPRENANT'];
    public function load(ObjectManager $manager)
    {
        foreach (self::ref as $value) {
            $profil = new Profil();
            $profil -> setLibelle($value);
            $this -> addReference($value, $profil);
            $manager -> persist($profil);
            $manager -> flush();
        }
    }
}
