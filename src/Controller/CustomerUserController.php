<?php

namespace App\Controller;

use App\Entity\CustomerUser;
use App\Repository\CustomerUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users')]
final class CustomerUserController extends AbstractController
{
    #[Route('', name: 'users', methods: ['GET'])]
    public function getUsersList(CustomerUserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $users = $userRepository->findBy(['customer' => $currentUser->getCustomer()]);
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user:read']);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'user', methods: ['GET'])]
    public function getUserDetail(CustomerUser $user, SerializerInterface $serializer): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser || $user->getCustomer() !== $currentUser->getCustomer()) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access denied for this resource.');
        }

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    /**
     * @throws HttpException
     */
    #[Route('', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $customerUser = $serializer->deserialize($request->getContent(), CustomerUser::class, 'json');

        $errors = $validator->validate($customerUser);
        if (count($errors) > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid request payload.');
        }

        $customerUser->setCustomer($currentUser->getCustomer());
        $em->persist($customerUser);
        $em->flush();

        $newUserJson = $serializer->serialize($customerUser, 'json', ['groups' => 'user:read']);
        $returnLocation = $this->generateUrl('user', ['id' => $customerUser->getId()]);

        return new JsonResponse($newUserJson, Response::HTTP_CREATED, ['Location' => $returnLocation], true);
    }

    #[Route('/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(CustomerUser $user, EntityManagerInterface $em): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser || $user->getCustomer() !== $currentUser->getCustomer()) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access denied for this resource.');
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}