<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use OpenApi\Attributes as OA;

#[Route('/api/customers')]
/**
 * @OA\Tag(name="Customers", description="Endpoints for managing customers")
 */
final class CustomerController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('', name: 'customers', methods: ['GET'])]
    public function getCustomersList(CustomerRepository $customerRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 5);

        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $idCache = "getCustomers-" . $page . "-" . $limit;

        $jsonUsers = $cache->get($idCache, function (ItemInterface $item) use ($customerRepository, $page, $limit, $serializer, $currentUser) {
            $item->tag('customersCache');
            $item->expiresAfter(3600);
            $customers = $customerRepository->findPaginatedCustomers($currentUser, $page, $limit);
            return $serializer->serialize($customers, 'json', ['groups' => 'customer:read']);
        });

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'customer', methods: ['GET'])]
    public function getCustomerDetail(Customer $customer, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser || $customer->getUser() !== $currentUser) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access denied for this resource.');
        }

        $idCache = "getCustomer-" . $customer->getId();

        $jsonUser = $cache->get($idCache, function (ItemInterface $item) use ($serializer, $customer) {
            $item->tag('customersCache');
            $item->expiresAfter(3600);
            return $serializer->serialize($customer, 'json', ['groups' => 'customer:read']);
        });

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('', name: 'create_customer', methods: ['POST'])]
    public function createCustomer(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json');
        $customer->setUser($currentUser);

        $errors = $validator->validate($customer);
        if (count($errors) > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid request payload.');
        }

        $em->persist($customer);
        $em->flush();

        $returnLocation = $this->generateUrl('customer', ['id' => $customer->getId()]);

        return new JsonResponse(null, Response::HTTP_CREATED, ['Location' => $returnLocation], true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'delete_customer', methods: ['DELETE'])]
    public function deleteCustomer(Customer $customer, EntityManagerInterface $em, TagAwareCacheInterface $cache): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser || $customer->getUser() !== $currentUser) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Access denied for this resource.');
        }

        $cache->invalidateTags(['customersCache']);
        $em->remove($customer);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}