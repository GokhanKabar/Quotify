<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\InvoiceDetail;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InvoiceDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < InvoiceFixtures::INVOICE_COUNT_REFERENCE; ++$i) {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->setQuantity(rand(1, 100));
            $invoiceDetail->setTva(rand(0, 100));
            $invoiceDetail->setInvoice($this->getReference(InvoiceFixtures::INVOICE_REFERENCE . rand(1, InvoiceFixtures::INVOICE_COUNT_REFERENCE)));
            $invoiceDetail->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE . rand(1, ProductFixtures::PRODUCT_COUNT_REFERENCE)));

            $manager->persist($invoiceDetail);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            InvoiceFixtures::class,
        ];
    }
}