<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;

final class ProfilPersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        $this->manager->persist($data);
        $this->manager->flush();
    }

    public function remove($data, array $context = [])
    {
        $data->setArchivage(true);
        $user=$data->getUsers();
        foreach ($user as $u){
            $u->setarchivage(true);
        }
        $this->manager->flush();
    }
}