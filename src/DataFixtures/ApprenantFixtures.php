<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Apprenant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $apprenant = new Apprenant();
        $password = $this->encoder->encodePassword($apprenant, 'passer');
        $apprenant->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setEmail($faker->email)
            ->setPassword($password)
            ->setTel($faker->phoneNumber)
            ->setAdresse($faker->city)
            ->setProfil($this->getReference(ProfilFixtures::Apprenant_Reference));

        $manager->persist($apprenant);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
