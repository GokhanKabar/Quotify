<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Invoice;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    const INVOICE_REFERENCE = 'invoice';
    const INVOICE_COUNT_REFERENCE = 10;
    const INVOICE_PAYMENT_STATUS = [
        'Payé',
        'Non payé',
        'En attente'
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::INVOICE_COUNT_REFERENCE; ++$i) {
            $invoice = new Invoice();
            $invoice->setPaymentStatus(self::INVOICE_PAYMENT_STATUS[rand(0, count(self::INVOICE_PAYMENT_STATUS) - 1)]);
            $invoice->setCreationDate($faker->dateTimeBetween('-1 years', 'now'));
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
