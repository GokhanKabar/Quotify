<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\QuotationDetail;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuotationDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $quotationDetail = new QuotationDetail();
        $quotationDetail->setQuantity(rand(1, 100));
        $quotationDetail->setSubTotal($faker->randomFloat(2, 0, 1000));
        $quotationDetail->setQuotation($this->getReference(QuotationFixtures::QUOTATION_REFERENCE . rand(1, QuotationFixtures::QUOTATION_COUNT_REFERENCE)));
        $quotationDetail->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE . rand(1, ProductFixtures::PRODUCT_COUNT_REFERENCE)));

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            QuotationFixtures::class,
        ];
    }
}
