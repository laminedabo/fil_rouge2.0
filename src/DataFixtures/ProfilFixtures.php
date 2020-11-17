<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    const ref = 'admin';
    public function load(ObjectManager $manager)
    {
        $profil = new Profil();
        $profil -> setLibelle('ADMIN');
        $this -> addReference(self::ref, $profil);
        $manager -> persist($profil);
        $manager -> flush();
    }
}
