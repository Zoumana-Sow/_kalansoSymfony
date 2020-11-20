<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public const Admin_Reference = 'Admin';
    public const Apprenant_Reference = 'Apprenant';
    public const CM_Reference = 'CM';
    public const Formateur_Reference = 'Formateur';

    public function load(ObjectManager $manager)
    {
        $profils = ["Admin", "Apprenant", "CM", "Formateur"];
        for ($i = 0; $i < count($profils); $i++) {
            $profil = new Profil();
            $profil->setLibelle($profils[$i]);
            if ($profils[$i] == "Admin") {
                $this->addReference(self::Admin_Reference, $profil);
            } elseif ($profils[$i] == "Apprenant") {
                $this->addReference(self::Apprenant_Reference, $profil);
            } elseif ($profils[$i] == "CM") {
                $this->addReference(self::CM_Reference, $profil);
            } else {
                $this->addReference(self::Formateur_Reference, $profil);
            }

            $manager->persist($profil);
        }
        $manager->flush();
    }
}

