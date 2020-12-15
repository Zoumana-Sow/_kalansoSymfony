<?php

namespace App\Controller;

use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tag;
use App\Entity\GroupeTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupeTagsController extends AbstractController
{
    private $serializer;
    private $em;
    private $validator;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function AddGrpeTags(Request $request)
    {

        $jsonRecu = json_decode ($request->getcontent(),true);

        $grpetags = $this->serializer->denormalize($jsonRecu, GroupeTag::class);

        foreach ($jsonRecu["tags"] as $d){
            $tag = $this->em->getRepository(Tag::class)->find($d["id"]);
            $grpetags->addTag($tag);
        }

        $this->em->persist($grpetags);
        $this->em->flush();
        return $this->json("success", 201, ['groups', 'gpetags:read']);
    }
}
