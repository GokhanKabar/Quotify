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

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::PRODUCT_COUNT_REFERENCE; ++$i) {
            $product = new Product();
            $product->setProductName($faker->name);
            $product->setDescription($faker->text);
            $product->setUnitPrice($faker->randomFloat(2, 0, 1000));
            $product->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE . rand(1, count(CategoryFixtures::CATEGORY_ARRAY))));

            $manager->persist($product);

            $this->addReference(sprintf('%s%d', self::PRODUCT_REFERENCE, $i + 1), $product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
