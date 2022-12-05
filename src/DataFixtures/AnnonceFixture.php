<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Doctrine\ommon\DataFixtures\DependentFixtureInterface;

class AnnonceFixture extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
       

        for ($i = 0; $i < 1000; $i++) {

            $annonces = new Annonce(); // création d'une nouvelle instance de Product
            $annonces
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setPrice($faker->numberBetween(10, 100))
                ->setStatus($faker->numberBetween(0, 4))
                ->setSold(false)
            ;

            $manager->persist($annonces); // cette ligne permet de dire à Doctrine que l'objet $product doit être inséré en base de données
            // on pourrait imaginer hydrater $product avec des setter
        }

        $manager->flush();
    }
}



