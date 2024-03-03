<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Invoice;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTime;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    const INVOICE_REFERENCE = 'invoice';
    const INVOICE_COUNT_REFERENCE = 10;
    const INVOICE_PAYMENT_STATUS = [
        'Payé',
        'En attente'
    ];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::INVOICE_COUNT_REFERENCE; ++$i) {
            $invoice = new Invoice();
            $formattedDate = new DateTime();

            $invoice->setCreationDate($formattedDate);

            $invoice->setPaymentStatus(self::INVOICE_PAYMENT_STATUS[rand(0, count(self::INVOICE_PAYMENT_STATUS) - 1)]);

            $invoice->setTotalHT(rand(0, 1000));
            $invoice->setTotalTTC(rand(0, 1200)); // Supposons que Total TTC est légèrement supérieur à HT
            $invoice->setUserReference($this->getReference(UserFixtures::USER_REFERENCE . rand(1, UserFixtures::USER_COUNT_REFERENCE)));

            $manager->persist($invoice);

            $this->addReference(sprintf('%s%d', self::INVOICE_REFERENCE, $i + 1), $invoice);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
