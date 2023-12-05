<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Quotation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuotationFixtures extends Fixture implements DependentFixtureInterface
{
    const QUOTATION_REFERENCE = 'quotation';
    const QUOTATION_COUNT_REFERENCE = 10;
    const QUOTATION_STATUS = [
        'En attente',
        'Approuvé',
        'Rejeté',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::QUOTATION_COUNT_REFERENCE; ++$i) {
            $quotation = new Quotation();
            $quotation->setTotalPrice($faker->randomFloat(2, 0, 1000));
            $quotation->setStatus(self::QUOTATION_STATUS[rand(0, count(self::QUOTATION_STATUS) - 1)]);
            $quotation->setCreationDate($faker->dateTimeBetween('-1 years', 'now'));
            $quotation->setUserReference($this->getReference(UserFixtures::USER_REFERENCE . rand(1, UserFixtures::USER_COUNT_REFERENCE)));
            $quotation->setFile((new FileFixtures())->createFileFromArray(
                FileFixtures::FILE_ARRAY
            ));

            $manager->persist($quotation);

            $this->addReference(sprintf('%s%d', self::QUOTATION_REFERENCE, $i + 1), $quotation);
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
