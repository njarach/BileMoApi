<?php

namespace App\Serializer;

use App\Entity\Customer;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerNormalizer implements NormalizerInterface
{
    private NormalizerInterface $normalizer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(#[Autowire(service: 'serializer.normalizer.object')] NormalizerInterface $normalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize($customer, $format = null, array $context = []): array
    {
        // Vérifier si c'est une liste d'objets Customer
        if (is_iterable($customer)) {
            return array_map(fn($customer) => $this->normalize($customer, $format, $context), $customer);
        }

        $data = $this->normalizer->normalize($customer, $format, array_merge($context, ['groups' => 'customer:read']));

        $data['details']['get'] = $this->urlGenerator->generate(
            'customer',
            ['id' => $customer->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $data['details']['delete'] = $this->urlGenerator->generate(
            'delete_customer',
            ['id' => $customer->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Customer;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Customer::class => true
        ];
    }
}