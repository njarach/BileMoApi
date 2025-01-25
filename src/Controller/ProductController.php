<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'products', methods: ['GET'])]
    public function getProductsList(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $jsonProductsList = $serializer->serialize($products,'json');
        return new JsonResponse($jsonProductsList,Response::HTTP_OK,[],true);
    }

    #[Route('/api/products/{id}', name: 'product', methods: ['GET'])]
    public function getProduct(int $id, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $product = $productRepository->find($id);
        if ($product) {
            $jsonProduct = $serializer->serialize($product, 'json');
            return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $em): JsonResponse
    {
        $product = $productRepository->find($id);
        if ($product) {
            $em->remove($product);
            $em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

}
