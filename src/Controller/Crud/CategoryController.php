<?php

namespace App\Controller\Crud;

use App\Entity\Category;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;
#[Route('/categories')]
class CategoryController extends AbstractController
{
    private ValidatorInterface $validator;
    private CategoriesRepository $categoriesRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, CategoriesRepository $categoriesRepository, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->categoriesRepository = $categoriesRepository;
        $this->entityManager = $entityManager;
    }
    #[OA\Get(
        path: '/categories',
        description: 'Gets all categories',
        responses:[
            new OA\Response(
                response: 200,description: 'success'
            )
        ]
    )]
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $response = [];
        $categories = $this->categoriesRepository->findAll();
        foreach ($categories as $category) {
            $response[] = json_encode($category);
        }
        return new JsonResponse([
            'success' => true,
            'data' => $response,
        ]);
    }
    #[OA\Get(
        path: '/categories/id',
        description: 'Gets a category by its id',
        responses:[
            new OA\Response(
                response: 200,description: 'success'
            )
        ]
    )]
    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return new JsonResponse([
            'success' => true,
            'data' => $category,
        ]);
    }
    #[OA\Post(
        path: '/categories/create',
        description: 'Creates a category',
        responses:[
            new OA\Response(
                response: 200,description: 'success'
            )
        ]
    )]
    #[Route('/create', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function create(Request $request,): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = new Category();
        $category->setName($data['name']);
        $violations = $this->validator->validate($category);
        if (count($violations) > 0) {
            return $this->json([
                'success' => false,
                'violations' => $violations
            ]);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return new JsonResponse([
            'success' => true,
        ]);
    }
    #[OA\PUT(
        path: '/categories/id/edit',
        description: 'Edits a category',
        responses:[
            new OA\Response(
                response: 200,description: 'success'
            )
        ]
    )]
    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['PUT'])]
    public function edit(Request $request, Category $category): Response
    {
        $data = json_decode($request->getContent(), true);
        $category->setName($data['name']);
        $violations = $this->validator->validate($category);
        if (count($violations) > 0) {
            return new JsonResponse([
                'success' => false,
                'violations' => $violations
            ]);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true]);
    }
    #[OA\Delete(
        path: '/categories/id',
        description: 'Deletes a category by its id',
        responses:[
            new OA\Response(
                response: 200,description: 'success'
            )
        ]
    )]
    #[Route('/{id}', name: 'app_category_delete', methods: ['DELETE'])]
    public function delete(Category $category): Response
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true]);
    }
}
