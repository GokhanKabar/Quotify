<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Quotation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTime;

class QuotationFixtures extends Fixture implements DependentFixtureInterface
{
    const QUOTATION_REFERENCE = 'quotation';
    const QUOTATION_COUNT_REFERENCE = 10;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::QUOTATION_COUNT_REFERENCE; ++$i) {
            $quotation = new Quotation();
            $formattedDate = new DateTime();

            $quotation->setStatus("Devis envoyé par mail le " . $formattedDate->format('d/m/Y H:i:s'));
            $quotation->setCreationDate($formattedDate);
            $quotation->setTotalHT(rand(0, 1000));
            $quotation->setTotalTTC(rand(0, 1200));
            $quotation->setUserReference($this->getReference(UserFixtures::USER_REFERENCE . rand(1, UserFixtures::USER_COUNT_REFERENCE)));

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
