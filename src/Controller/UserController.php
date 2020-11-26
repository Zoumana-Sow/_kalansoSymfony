<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\CM;
use App\Entity\Formateur;
use App\Entity\Profil;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    private $serializer;
    private $security;

    public function __construct(SerializerInterface $serializer,Security $security)
    {
        $this->serializer=$serializer;
        $this->security = $security;
    }
    //create user
    public function addUser(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = $request->request->all();
        $avatar = $request->files->get("avatar");
        $manager=$this->getDoctrine()->getManager();
        $profil=$manager->getRepository(Profil::class)->findOneBy(['libelle' => $user['profils']]);
        //Savoir quel user on va inseré
        if ($user['profils'] == "Apprenant") {
            $user = $serializer->denormalize($user,Apprenant::class,true);
        }elseif ($user['profils'] == "Formateur") {
            $user = $serializer->denormalize($user,Formateur::class,true);
        }elseif ($user['profils'] == "CM") {
            $user = $serializer->denormalize($user,CM::class,true);
        }else{
            $user = $serializer->denormalize($user,"App\Entity\User",true);
        }
        $avatar = fopen($avatar->getRealPath(),"rb");
        $user->setAvatar($avatar);
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setProfil($profil);
        $em->persist($user);
        $em->flush();
        fclose($avatar);
        return $this->json("success",201);
    }



}
