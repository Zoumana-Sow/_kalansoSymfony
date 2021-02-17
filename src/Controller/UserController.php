<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\CM;
use App\Entity\Formateur;
use App\Entity\Profil;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    private $em;
    private $validator;

    public function __construct(SerializerInterface $serializer,Security $security,EntityManagerInterface $em,ValidatorInterface $validator)
    {
        $this->serializer=$serializer;
        $this->security = $security;
        $this->em =  $em;
        $this->validator = $validator;
    }
    //create user
    public function addUser(Request $request, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        $user = $request->request->all();
        $uploadfile = $request->files->get("avatar");
        $manager=$this->getDoctrine()->getManager();
        $profil=$manager->getRepository(Profil::class)->findOneBy(['libelle' => $user['profils']]);
        //Savoir quel user on va inseré
       // return $this->json($user);
        if ($user['profils'] == "Apprenant") {
            $user = $serializer->denormalize($user,Apprenant::class,true);
        }elseif ($user['profils'] == "Formateur") {
            $user = $serializer->denormalize($user,Formateur::class,true);
        }elseif ($user['profils'] == "CM") {
            $user = $serializer->denormalize($user,CM::class,true);
        }else{
            $user = $serializer->denormalize($user,"App\Entity\User",true);
        }
        $file = $uploadfile->getRealPath();
        $avatar = fopen($file,'r+');
        $user->setAvatar($avatar);
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setProfil($profil);
        $this->em->persist($user);
        $this->em->flush();
        fclose($avatar);
        return $this->json("success 2",201);
    }
    //edit user
    public function editUser(Request $request,int $id)
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if(!($this->isGranted('ROLE_Admin') ||  $user==$this->getUser()))
        {
            return $this->json('Vous n avez pas accès à cette ressource',400);
        }

        $data = $request->request->all();
        // return $this->json($data, 400);
        $test=[];
        foreach($data as $key => $d)
        {
            if($key!== "id" && $key !== "profil" && $key!== "groupes")// je ne comprends plus cest a cause de l'id que ça cale
            {
                $user->{"set".ucfirst($key)}($d);
                //
            }
        }

        //return $this->json($test, 400);
        $uploadfile = $request->files->get("avatar");
        if($uploadfile)
        {
            $file = $uploadfile ->getRealPath();
            $avatar = fopen($file,"r+");
            $user->setAvatar($avatar);
        }
        $user->setArchivage(false);
        $this->em->persist($user);
        $this->em->flush();
        return $this->json("success 3",201);

    }
//    //Liste des apprenants
//    public function showApprenants(UserRepository $repo)
//    {
//        if($this->isGranted('ROLE_Formateur') || $this->isGranted('ROLE_Admin')  || $this->isGranted('ROLE_CM')){
//            $apprenants = $repo->findByProfil("Apprenant");
//            return $this->json($apprenants,200,[],['groups'=>'user:read']);
//        }else{
//            return $this->json("vous n'avez pas accès a cette resource ",403);
//        }
//    }
//    //liste des formateurs
//    public function showFormateurs(UserRepository $repo)
//    {
//        if($this->isGranted('ROLE_Admin')  || $this->isGranted('ROLE_CM')){
//            $formateurs = $repo->findByProfil("Formateur");
//            return $this->json($formateurs,200,[],['groups'=>'user:read']);
//        }else{
//            return $this->json("vous n'avez pas accès a cette resource ",403);
//        }
//    }
//    //get one apprenant by id
//    public function findApprenantsById(UserRepository $repo,$id)
//    {
//        if($this->isGranted('ROLE_Admin') || $this->isGranted('ROLE_Formateur') || $this->isGranted('ROLE_CM')){
//            $apprenants = $repo->findOneById('Apprenant', $id);
//            if ($apprenants) {
//                return $this->json($apprenants,Response::HTTP_OK,[],['groups'=>"apprenant:read"]);
//            }else{
//                return $this->json("user n'est pas un apprenant");
//            }
//        }else {
//            $apprenants = $repo->findOneById('Apprenant', $id);
//
//            $user=$this->security->getUser();
//            if (!$apprenants)
//            { return $this->json("vous n'avez pas accès a cette resource ",403); }
//
//            if ( $this->isGranted('ROLE_Apprenant') && $apprenants->getId()==$user->getId() ) {
//                return $this->json($apprenants,Response::HTTP_OK,[],['groups'=>"student:read"]);
//            }else {
//                return $this->json("vous n'avez pas accès a cette resource ",403);
//            }
//
//        }
//    }
//    //get one formateur by id
//    public function findFormateursById(UserRepository $repo,$id)
//    {
//        if($this->isGranted('ROLE_CM') || $this->isGranted('ROLE_Admin')){
//            $formateurs = $repo->findOneById('Formateur', $id);
//            if ($formateurs) {
//                return $this->json($formateurs,Response::HTTP_OK,[],['groups'=>'user:read']);
//            }else{
//                return $this->json("user n'est pas un formateur");
//            }
//        }
//
//        else {
//            $formateurs = $repo->findOneById('Formateur', $id);
//            $user=$this->security->getUser();
//            if ($formateurs==null)
//            { return $this->json("vous n'avez pas accès a cette resource ",403); }
//
//            if ( $this->isGranted('ROLE_Formateur') && $user->getId()==$formateurs->getId() ) {
//                return $this->json($formateurs,Response::HTTP_OK,[],['groups'=>'user:read']);
//            }
//            else {
//                return $this->json("vous n'avez pas accès a cette resource ",403);
//            }
//        }
//    }
//    //edit apprenant by id
//    public function editApprenant(UserRepository $repo,$id,Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
//    {
//        $apprenantObject = $repo->findOneById('Apprenant', $id);
//        if($apprenantObject==null ) {
//            return $this->json("vous n'avez pas accès a cette ressource ",403);
//        }
//        $user=$this->security->getUser();
//        if($this->isGranted('ROLE_Formateur') || $this->isGranted('ROLE_Admin') || ($this->isGranted('ROLE_Apprenant') && $user->getId()==$apprenantObject->getId()) ){
//            $jsonApprenant  = json_decode($request->getContent());
//
//            $apprenantObject->setLastname($jsonApprenant->nom);
//            $apprenantObject->setFirstname($jsonApprenant->prenom);
//
//            if($apprenantObject){
//                $erreurs = $validator->validate($apprenantObject);
//                if (count($erreurs)>0) {
//                    return $this->json('invalide',Response::HTTP_BAD_REQUEST);
//                }
//                $em->flush();
//                return $this->json('success 4',Response::HTTP_OK);
//            }else{
//                return $this->json("user n'est pas un apprenant");
//            }
//        }else{
//            return $this->json("vous n'avez pas accès a cette resource ",403);
//        }
//    }
//    //edit formateur by id
//    public function editFormateur(UserRepository $repo,$id,Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
//    {
//        $formateurObject = $repo->findOneById('FORMATEUR', $id);
//        if($formateurObject==null ) {
//            return $this->json("vous n'avez pas accès a cette resource ",403);
//        }
//        $user=$this->security->getUser();
//        if( ($this->isGranted('ROLE_FORMATEUR') && $user->getId()==$formateurObject->getId())  || $this->isGranted('ROLE_ADMIN') ){
//
//            $jsonFormateur  = json_decode($request->getContent());
//
//            $formateurObject->setNom($jsonFormateur->nom);
//            $formateurObject->setPrenom($jsonFormateur->prenom);
//            $formateurObject->setEmail($jsonFormateur->email);
//
//            if($formateurObject){
//                $erreurs = $validator->validate($formateurObject);
//                if (count($erreurs)>0) {
//                    return $this->json('invalide',Response::HTTP_BAD_REQUEST);
//                }
//                $em->flush();
//                return $this->json('success 5',Response::HTTP_OK);
//            }else{
//                return $this->json("user n'est pas un apprenant");
//            }
//        }else{
//
//            return $this->json("vous n'avez pas accès a cette resource ",403);
//        }
//    }
}
