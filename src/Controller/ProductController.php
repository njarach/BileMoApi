<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    #[Route('', name: 'products', methods: ['GET'])]
    public function getProductsList(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $products = $productRepository->findAll();
        $jsonProductsList = $serializer->serialize($products, 'json');

        return new JsonResponse($jsonProductsList, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'product', methods: ['GET'])]
    public function getProduct(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Authentication is required.');
        }

        $jsonProduct = $serializer->serialize($product, 'json');

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }

    /* This is commented because customers don't need a DELETE endpoint.
     * #[Route('/{id}', name: 'delete_product', methods: ['DELETE'])]
     public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $em): JsonResponse
     {
         $product = $productRepository->find($id);
         if ($product) {
             $em->remove($product);
             $em->flush();
             return new JsonResponse(null, Response::HTTP_NO_CONTENT);
         }
         return new JsonResponse(null, Response::HTTP_NOT_FOUND);
     }*/

   /* This is commented because customers don't need a POST endpoint.
    * #[Route('', name: 'create_product', methods: ['POST'])]
    public function createProduct(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager):JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(),Product::class,'json');
        $manager->persist($product);
        $manager->flush();
        $newProductJson = $serializer->serialize($product,'json');
        $returnLocation = $this->generateUrl('product',['id'=>$product->getId()]);
        return new JsonResponse($newProductJson,Response::HTTP_CREATED,['Location'=>$returnLocation],true);
    }*/

    /* This is commented because customers don't need a PUT endpoint.
     * #[Route('/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(int $id, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager):JsonResponse
    {
        $foundProduct = $manager->getRepository(Product::class)->findOneBy(['id'=>$id]);
        if (!$foundProduct) {
            return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        }

        $updatedProduct = $serializer->deserialize($request->getContent(),Product::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $foundProduct]);
        $manager->persist($updatedProduct);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }*/

}
