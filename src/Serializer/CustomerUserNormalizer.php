<?php

namespace App\Serializer;

use App\Entity\CustomerUser;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerUserNormalizer implements NormalizerInterface
{
    private NormalizerInterface $normalizer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(#[Autowire(service: 'serializer.normalizer.object')] NormalizerInterface $normalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize($customerUser, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($customerUser, $format, array_merge($context, ['groups' => 'user:read']));

        $data['details']['self'] = $this->urlGenerator->generate(
            'user',
            ['id' => $customerUser->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $data['details']['delete'] = $this->urlGenerator->generate(
            'delete_user',
            ['id' => $customerUser->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CustomerUser;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            CustomerUser::class => true
        ];
    }
}