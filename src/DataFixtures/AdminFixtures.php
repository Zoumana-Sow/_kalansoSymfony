<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $admin = new User();
        $password = $this->encoder->encodePassword($admin, '@Admin');
        $admin->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setEmail($faker->email)
            ->setPassword($password)
            ->setTel($faker->phoneNumber)
            ->setAdresse($faker->city)
            ->setProfil($this->getReference(ProfilFixtures::Admin_Reference));

        $manager->persist($admin);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
