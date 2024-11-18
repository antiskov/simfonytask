<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends AbstractController
{

    private UserService $userService;
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    #[Route('users/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserDetails($id);
            return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user_read']);
        } catch (Exception $e) {
            $statusCode = $e instanceof AccessDeniedException
            ? JsonResponse::HTTP_FORBIDDEN
            : JsonResponse::HTTP_NOT_FOUND;

            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('users', methods: ['POST'])]
    public function create(
        Request $request): JsonResponse
    {
        try {
            $data = [
                'id' => $request->get('id'),
                'login' => $request->get('login'),
                'pass' => $request->get('pass'),
                'phone' => $request->get('phone'),
                'roles' => $request->get('roles', ['ROLE_TEST_USER'])
            ];
            
            $user = $this->userService->createUser($data);
            return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user_read']);
        } catch (Exception $e) {
            $statusCode = $e instanceof AccessDeniedException
            ? JsonResponse::HTTP_FORBIDDEN
            : JsonResponse::HTTP_BAD_REQUEST;

            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('users/{id}', methods: ['PUT'])]
    public function update(
        Request $request,
        int $id): JsonResponse
    {
        try {
            $data = [
                'login' => $request->get('login'),
                'pass' => $request->get('pass'),
                'phone' => $request->get('phone'),
                'roles' => $request->get('roles', ['ROLE_TEST_USER'])
            ];

            $user = $this->userService->editUser($id, $data);
            return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user_read']);
        } catch (Exception $e) {
            $statusCode = $e instanceof AccessDeniedException
            ? JsonResponse::HTTP_FORBIDDEN
            : JsonResponse::HTTP_BAD_REQUEST;

            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('users/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse|Response
    {
        try {
            $this->userService->deleteUser($id);
            return new Response();
        } catch (Exception $e) {
            $statusCode = $e instanceof AccessDeniedException
            ? JsonResponse::HTTP_FORBIDDEN
            : JsonResponse::HTTP_NOT_FOUND;

            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}