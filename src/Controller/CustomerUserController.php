<?php

namespace App\Controller;

use App\Entity\CustomerUser;
use App\Repository\CustomerUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/users')]
final class CustomerUserController extends AbstractController
{
    /**
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    #[Route('', name: 'users', methods: ['GET'])]
    public function getUsersList(CustomerUserRepository $userRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);

        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $idCache = "getUsers-" . $page . "-" . $limit;

        $jsonUsers = $cache->get($idCache, function (ItemInterface $item) use ($userRepository, $page, $limit, $serializer, $currentUser) {
            $item->tag('usersCache');
            $item->expiresAfter(3600);
            $users = $userRepository->findPaginatedCustomerUsers($currentUser->getCustomer(), $page, $limit);
            return $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        });

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'user', methods: ['GET'])]
    public function getUserDetail(CustomerUser $user, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser || $user->getCustomer() !== $currentUser->getCustomer()) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access denied for this resource.');
        }

        $idCache = "getUser-" . $user->getId();

        $jsonUser = $cache->get($idCache, function (ItemInterface $item) use ($serializer, $user) {
            $item->tag('usersCache');
            $item->expiresAfter(3600);
            return $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        });

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    /**
     * @throws HttpException
     */
    #[Route('', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $customerUser = $serializer->deserialize($request->getContent(), CustomerUser::class, 'json');
        $customerUser->setCustomer($currentUser->getCustomer());
        $plaintextPassword = $customerUser->getPassword();
        $hashedPassword = $passwordHasher->hashPassword($customerUser, $plaintextPassword);
        $customerUser->setPassword($hashedPassword);
        $customerUser->setRoles(['ROLE_USER']);

        $errors = $validator->validate($customerUser);
        if (count($errors) > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid request payload.');
        }

        $em->persist($customerUser);
        $em->flush();

        $newUserJson = $serializer->serialize($customerUser, 'json', ['groups' => 'user:read']);
        $returnLocation = $this->generateUrl('user', ['id' => $customerUser->getId()]);

        return new JsonResponse($newUserJson, Response::HTTP_CREATED, ['Location' => $returnLocation], true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(CustomerUser $user, EntityManagerInterface $em, TagAwareCacheInterface $cache): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser || $user->getCustomer() !== $currentUser->getCustomer()) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access denied for this resource.');
        }

        $cache->invalidateTags(['usersCache']);
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}