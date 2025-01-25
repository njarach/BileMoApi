<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        {
            $productsData = [
                [
                    'name' => 'iPhone 14 Pro',
                    'description' => 'Le dernier modèle de la gamme iPhone avec une puce A16 Bionic et un écran ProMotion 120 Hz.',
                    'brand' => 'Apple',
                    'reference' => 'APL-IPH14P',
                    'unit_price_tax_incl' => '1299.99',
                    'unit_price_tax_excl' => '1083.33',
                ],
                [
                    'name' => 'Samsung Galaxy S23 Ultra',
                    'description' => 'Un téléphone haut de gamme avec un capteur photo de 200 MP et une batterie longue durée.',
                    'brand' => 'Samsung',
                    'reference' => 'SMG-GS23U',
                    'unit_price_tax_incl' => '1399.99',
                    'unit_price_tax_excl' => '1166.66',
                ],
                [
                    'name' => 'Google Pixel 8 Pro',
                    'description' => 'Le flagship de Google avec des performances exceptionnelles et une expérience Android pure.',
                    'brand' => 'Google',
                    'reference' => 'GOO-PIX8P',
                    'unit_price_tax_incl' => '1199.99',
                    'unit_price_tax_excl' => '999.99',
                ],
                [
                    'name' => 'OnePlus 11',
                    'description' => 'Un smartphone puissant avec un écran AMOLED et une recharge ultra rapide.',
                    'brand' => 'OnePlus',
                    'reference' => 'OP-11',
                    'unit_price_tax_incl' => '849.99',
                    'unit_price_tax_excl' => '708.33',
                ],
                [
                    'name' => 'Xiaomi Mi 13',
                    'description' => 'Un smartphone élégant avec un processeur Snapdragon et un capteur photo Leica.',
                    'brand' => 'Xiaomi',
                    'reference' => 'XMI-MI13',
                    'unit_price_tax_incl' => '949.99',
                    'unit_price_tax_excl' => '791.66',
                ],
                [
                    'name' => 'Sony Xperia 1 V',
                    'description' => 'Un smartphone destiné aux créateurs de contenu avec un écran 4K OLED.',
                    'brand' => 'Sony',
                    'reference' => 'SNY-XP1V',
                    'unit_price_tax_incl' => '1299.99',
                    'unit_price_tax_excl' => '1083.33',
                ],
                [
                    'name' => 'Huawei P60 Pro',
                    'description' => 'Un smartphone haut de gamme avec des capacités photo exceptionnelles.',
                    'brand' => 'Huawei',
                    'reference' => 'HW-P60P',
                    'unit_price_tax_incl' => '1099.99',
                    'unit_price_tax_excl' => '916.66',
                ],
                [
                    'name' => 'Asus ROG Phone 7',
                    'description' => 'Un téléphone spécialement conçu pour les gamers avec des performances optimisées.',
                    'brand' => 'Asus',
                    'reference' => 'ASU-ROG7',
                    'unit_price_tax_incl' => '1199.99',
                    'unit_price_tax_excl' => '999.99',
                ],
                [
                    'name' => 'Oppo Find X6 Pro',
                    'description' => 'Un smartphone innovant avec un design unique et des capacités photo avancées.',
                    'brand' => 'Oppo',
                    'reference' => 'OPP-FX6P',
                    'unit_price_tax_incl' => '999.99',
                    'unit_price_tax_excl' => '833.33',
                ],
                [
                    'name' => 'Nokia XR20',
                    'description' => 'Un téléphone robuste conçu pour résister aux conditions extrêmes.',
                    'brand' => 'Nokia',
                    'reference' => 'NOK-XR20',
                    'unit_price_tax_incl' => '699.99',
                    'unit_price_tax_excl' => '583.33',
                ],
                [
                    'name' => 'Realme GT 3',
                    'description' => 'Un smartphone au rapport qualité-prix imbattable avec une recharge rapide de 240W.',
                    'brand' => 'Realme',
                    'reference' => 'RLM-GT3',
                    'unit_price_tax_incl' => '749.99',
                    'unit_price_tax_excl' => '624.99',
                ],
                [
                    'name' => 'Vivo X90 Pro',
                    'description' => 'Un téléphone premium avec des capacités photo avancées co-développées avec Zeiss.',
                    'brand' => 'Vivo',
                    'reference' => 'VIV-X90P',
                    'unit_price_tax_incl' => '1049.99',
                    'unit_price_tax_excl' => '874.99',
                ],
                [
                    'name' => 'Fairphone 4',
                    'description' => 'Un téléphone durable et éthique, conçu pour être facilement réparable.',
                    'brand' => 'Fairphone',
                    'reference' => 'FAI-4',
                    'unit_price_tax_incl' => '649.99',
                    'unit_price_tax_excl' => '541.66',
                ],
                [
                    'name' => 'Motorola Edge 40',
                    'description' => 'Un smartphone mince et puissant avec un écran incurvé.',
                    'brand' => 'Motorola',
                    'reference' => 'MOT-EDGE40',
                    'unit_price_tax_incl' => '799.99',
                    'unit_price_tax_excl' => '666.66',
                ],
                [
                    'name' => 'Apple iPhone SE (2023)',
                    'description' => 'Un iPhone compact et abordable avec une puce A15 Bionic.',
                    'brand' => 'Apple',
                    'reference' => 'APL-IPSE23',
                    'unit_price_tax_incl' => '479.99',
                    'unit_price_tax_excl' => '399.99',
                ],
                [
                    'name' => 'Samsung Galaxy Z Fold 5',
                    'description' => 'Le téléphone pliable de Samsung avec un écran massif et des performances haut de gamme.',
                    'brand' => 'Samsung',
                    'reference' => 'SMG-GZF5',
                    'unit_price_tax_incl' => '1799.99',
                    'unit_price_tax_excl' => '1499.99',
                ],
                [
                    'name' => 'Xiaomi Redmi Note 12 Pro',
                    'description' => 'Un téléphone milieu de gamme avec d’excellentes performances pour son prix.',
                    'brand' => 'Xiaomi',
                    'reference' => 'XMI-RN12P',
                    'unit_price_tax_incl' => '499.99',
                    'unit_price_tax_excl' => '416.66',
                ],
                [
                    'name' => 'Sony Xperia 5 V',
                    'description' => 'Une version compacte de la gamme Xperia pour ceux qui préfèrent un téléphone plus petit.',
                    'brand' => 'Sony',
                    'reference' => 'SNY-XP5V',
                    'unit_price_tax_incl' => '999.99',
                    'unit_price_tax_excl' => '833.33',
                ],
                [
                    'name' => 'Huawei Mate X3',
                    'description' => 'Un téléphone pliable innovant avec une grande surface d’écran.',
                    'brand' => 'Huawei',
                    'reference' => 'HW-MX3',
                    'unit_price_tax_incl' => '2099.99',
                    'unit_price_tax_excl' => '1749.99',
                ],
                [
                    'name' => 'Asus Zenfone 10',
                    'description' => 'Un téléphone compact mais puissant, idéal pour une utilisation d’une seule main.',
                    'brand' => 'Asus',
                    'reference' => 'ASU-ZF10',
                    'unit_price_tax_incl' => '849.99',
                    'unit_price_tax_excl' => '708.33',
                ],
            ];

            foreach ($productsData as $data) {
                $product = new Product();
                $product->setName($data['name']);
                $product->setDescription($data['description']);
                $product->setBrand($data['brand']);
                $product->setReference($data['reference']);
                $product->setUnitPriceTaxIncl($data['unit_price_tax_incl']);
                $product->setUnitPriceTaxExcl($data['unit_price_tax_excl']);
                $manager->persist($product);
            }

            $manager->flush();
        }
    }
}
