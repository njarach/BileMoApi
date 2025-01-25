<?php

namespace App\Controller;

use App\Entity\CustomerUser;
use App\Repository\CustomerUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users')]
final class CustomerUserController extends AbstractController
{
    #[Route('', name: 'users', methods: ['GET'])]
    public function getUsersList(CustomerUserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        // TODO : add proper security and authentication to use this
//        $currentUser = $this->getUser();
//        if (!$currentUser) {
//            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
//        }
        $users = $userRepository->findBy(['customer'=>1]);
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'user', methods: ['GET'])]
    public function getUserDetail(CustomerUser $user, SerializerInterface $serializer): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser || $user->getCustomer() !== $currentUser->getCustomer()) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('', name: 'create_user', methods: ['POST'])]
    public function createUser( Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $user = new CustomerUser();
        $user->setFirstname($data['firstname'] ?? null);
        $user->setLastname($data['lastname'] ?? null);
        $user->setCustomer($currentUser->getCustomer());
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        $em->persist($user);
        $em->flush();

        $newUserJson = $serializer->serialize($user,'json');
        $returnLocation = $this->generateUrl('user',['id'=>$user->getId()]);
        return new JsonResponse($newUserJson, Response::HTTP_CREATED, ['Location'=>$returnLocation]);
    }

    #[Route('/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(CustomerUser $user,EntityManagerInterface $em): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser || $user->getCustomer() !== $currentUser->getCustomer()) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
