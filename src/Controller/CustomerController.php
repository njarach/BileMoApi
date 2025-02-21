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
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentification requise.');
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
    public function getCustomerDetail(?Customer $customer, SerializerInterface $serializer, TagAwareCacheInterface $cache, int $id): JsonResponse
    {
        if (!$customer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Client introuvable pour l'id : $id");
        }

        $currentUser = $this->getUser();
        if (!$currentUser || $customer->getUser() !== $currentUser) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Accès refusé. Vous ne disposez pas des droits requis pour effectuer cette action.');
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
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentification requise.');
        }

        try {
            $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json', ['groups' => 'customer:write']);
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Format ou structure JSON invalide');
        }

        $customer->setUser($currentUser);

        $errors = $validator->validate($customer);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new HttpException(Response::HTTP_BAD_REQUEST,'Les données fournies ne sont pas valides : ' . json_encode($errorMessages)
            );
        }

        $em->persist($customer);
        $em->flush();

        $jsonCustomer = $serializer->serialize($customer, 'json', ['groups' => 'customer:read']);
        $returnLocation = $this->generateUrl('customer', ['id' => $customer->getId()]);

        return new JsonResponse($jsonCustomer, Response::HTTP_CREATED, ['Location' => $returnLocation], true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'delete_customer', methods: ['DELETE'])]
    public function deleteCustomer(?Customer $customer, EntityManagerInterface $em, TagAwareCacheInterface $cache, int $id): JsonResponse
    {
        if (!$customer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Client introuvable pour l'id : $id");
        }

        $currentUser = $this->getUser();
        if (!$currentUser || $customer->getUser() !== $currentUser) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Accès refusé. Vous ne disposez pas des droits requis pour effectuer cette action.');
        }

        $cache->invalidateTags(['customersCache']);
        $em->remove($customer);
        $em->flush();

        return new JsonResponse('Client supprimé avec succès.', 200);
    }
}