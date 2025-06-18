<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    /**
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    #[Route('', name: 'products', methods: ['GET'])]
    public function getProductsList(ProductRepository $productRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentification requise.');
        }

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);

        $idCache = "getProducts-" . $page . "-" . $limit;

        $jsonProductsList = $cache->get($idCache, function (ItemInterface $item) use ($productRepository, $page, $limit, $serializer) {
            $item->tag('productsCache');
            $item->expiresAfter(3600);
            $products = $productRepository->findPaginatedProducts($page, $limit);

            return $serializer->serialize($products, 'json', ['groups'=>'product:read']);
        });

        return new JsonResponse($jsonProductsList, Response::HTTP_OK, [], true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'product', methods: ['GET'])]
    public function getProduct(?Product $product, SerializerInterface $serializer, TagAwareCacheInterface $cache, int $id): JsonResponse
    {
        if (!$product) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Produit introuvable pour l'id : $id");
        }

        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentification requise.');
        }

        $idCache = "getProduct-" . $product->getId();

        $jsonProduct = $cache->get($idCache, function (ItemInterface $item) use ($serializer, $product) {
            $item->tag('productsCache');
            $item->expiresAfter(3600);
            return $serializer->serialize($product, 'json');
        });

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }
}
