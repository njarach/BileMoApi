<?php

namespace App\Serializer;

use App\Entity\Product;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductNormalizer implements NormalizerInterface
{
    private NormalizerInterface $normalizer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(#[Autowire(service: 'serializer.normalizer.object')] NormalizerInterface $normalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize($product, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($product, $format, array_merge($context, ['groups' => 'product:read']));

        $data['details']['self'] = $this->urlGenerator->generate(
            'product',
            ['id' => $product->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Product;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Product::class => true
        ];
    }
}