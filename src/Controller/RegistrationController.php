<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    private UserFactory $userFactory;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, UserFactory $userFactory)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->userFactory=$userFactory;
    }

    #[OA\Post(
        path:'/registration',
        description: "Registers a new user",responses: [
            new OA\Response(response: 200,description: 'success')
    ]
    )]
    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function newUser(Request $request): Response
    {
        $data=json_decode($request->getContent(),true);
        $user=$this->userFactory->create($data,$this->userPasswordHasher);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->json([
            'message'=>'User created',
            'user'=>$user
        ]);
    }
}
