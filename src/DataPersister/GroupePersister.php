<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Apprenant;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;

final class GroupePersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Groupe;
    }

    public function persist($data, array $context = [])
    {
        $this->manager->persist($data);
        $this->manager->flush();
    }

    public function remove($data, array $context = [])
    {
        $id=$data->getId();
        //$this->manager->getRepository(Apprenant::class)->find()
        $users=$data->getUsers();
        dd($users);
        foreach ($users as $user){
            $user->d();
        }
        $this->manager->flush();
    }
}