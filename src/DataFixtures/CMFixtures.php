<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\CM;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class CMFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $cm = new CM();
        $password = $this->encoder->encodePassword($cm, 'son@tel');
        $cm->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setEmail($faker->email)
            ->setPassword($password)
            ->setTel($faker->phoneNumber)
            ->setAdresse($faker->city)
            ->setProfil($this->getReference(ProfilFixtures::CM_Reference));

        $manager->persist($cm);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
