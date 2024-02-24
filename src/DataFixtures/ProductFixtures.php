<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    const PRODUCT_REFERENCE = 'product';
    const PRODUCT_COUNT_REFERENCE = 10;
    const PRODUCT_ARRAY = [
        "MacBook Air", "Dell XPS 13", "HP Spectre x360", "Lenovo ThinkPad X1 Carbon",
        "Dell Inspiron Desktop", "HP Pavilion Desktop", "Lenovo IdeaCentre",
        "iPad Pro", "Samsung Galaxy Tab S7", "Microsoft Surface Pro",
        "iPhone 13", "Samsung Galaxy S21", "Google Pixel 6", "OnePlus 9",
        "HP OfficeJet Pro", "Canon PIXMA", "Epson EcoTank", "Brother HL-L2350DW",
        "Seagate Backup Plus Portable", "Western Digital My Passport", "Samsung T5 Portable SSD",
        "SanDisk Ultra Flair", "Kingston DataTraveler", "Samsung BAR Plus",
        "Dell UltraSharp U2720Q", "ASUS ProArt Display", "LG UltraFine",
        "NVIDIA GeForce RTX 3080", "AMD Radeon RX 6900 XT",
        "Corsair Vengeance LPX", "G.Skill Ripjaws V", "Kingston HyperX Fury",
        "Intel Core i9-12900K", "AMD Ryzen 9 5900X",
        "Logitech MX Master 3", "Apple Magic Keyboard", "Microsoft Surface Arc Mouse",
        "Logitech C920 HD Pro", "Razer Kiyo", "Microsoft LifeCam HD-3000",
        "Sony WH-1000XM4", "AirPods Pro", "Bose QuietComfort 45", "Sennheiser HD 660S",
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::PRODUCT_COUNT_REFERENCE; ++$i) {
            $product = new Product();
            
            $product->setProductName(self::PRODUCT_ARRAY[$i]);
            $product->setDescription($faker->sentence(10, true));
            $product->setUnitPrice($faker->randomFloat(2, 0, 2000));
            $product->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE . rand(1, count(CategoryFixtures::CATEGORY_ARRAY))));
            $product->setCompanyReference($this->getReference(CompanyFixtures::COMPANY_REFERENCE . rand(1, CompanyFixtures::COMPANY_COUNT_REFERENCE)));

            $manager->persist($product);

            $this->addReference(sprintf('%s%d', self::PRODUCT_REFERENCE, $i + 1), $product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
