<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Repository\AnnoncesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixture extends Fixture implements DependentFixtureInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $users = $this->userRepository->findAll();
        $usersLength = count($users)-1;

        for ($i = 0; $i < 1000; $i++) {

            $randomKey = rand(0, $usersLength);
            $user = $users[$randomKey];

            $annonces = new Annonce(); // création d'une nouvelle instance de Product
            $annonces
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setImageUrl($faker->imageUrl(640, 480, 'duck', true))
                ->setPrice($faker->numberBetween(10, 100))
                ->setStatus($faker->numberBetween(0, 4))
                ->setUser($user)
                ->setSold(false)
            ;

            $manager->persist($annonces); // cette ligne permet de dire à Doctrine que l'objet $product doit être inséré en base de données
            // on pourrait imaginer hydrater $product avec des setter
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            UserFixtures::class,
        );
    }
}
