<?php

namespace App\DataFixtures;
use App\Entity\CM;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
    
        for ($j=0; $j < 3; $j++) { 
            $user = new User();                 
            $user -> setEmail($faker -> unique() -> email());
            $user -> setUsername(strtolower($faker->name()));
            $user -> setProfil($this -> getReference(ProfilFixtures::ref));
            $password = $this -> encoder -> encodePassword($user,"passe");
            $user -> setPassword($password);
            $manager -> persist($user);
            $manager -> flush();
        }
    }

    //Executer ProfilFixtures avant cette classe ci pour eviter des erreurs
    public function getDependencies(){
        return array(
            ProfilFixtures::class,
        );
    }
}
