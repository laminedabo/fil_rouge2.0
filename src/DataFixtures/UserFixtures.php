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

        for ($j=0; $j < 12; $j++) {
            if ($j<3) {
                $user = new User();
                $user -> setProfil($this -> getReference("ADMIN"));
            }
            elseif($j<6) {
                $user = new CM();
                $user -> setProfil($this -> getReference("CM"));
            }
            elseif ($j<9) {
                $user = new Formateur();
                $user -> setProfil($this -> getReference("FORMATEUR"));
            }
            else {
                $user = new Apprenant();    
                $user -> setProfil($this -> getReference("APPRENANT"));
            }             
            $user -> setEmail($faker -> unique() -> email());
            $user -> setUsername(strtolower($faker->name()));
            $password = $this -> encoder -> encodePassword($user,"passe");
            $user -> setPassword($password);
            $user->setNom($faker->lastname);
            $user->setPrenom($faker->firstname);
            $user->setAvatar($faker->imageUrl());
            $user -> setAdresse($faker->address);
            $user->setUsername(strtolower($faker->name()));
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
