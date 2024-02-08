<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    const COMPANY_COUNT_REFERENCE = 10;
    const COMPANY_REFERENCE = 'company';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::COMPANY_COUNT_REFERENCE; ++$i) {
            $company = new Company();
            $company->setCompanyName($faker->name);
            $company->setAddress($faker->address);
            $company->setEmail($faker->email);
            $company->setSiretNumber($faker->randomNumber(14, true));

            $manager->persist($company);

            $this->addReference(sprintf('%s%d', self::COMPANY_REFERENCE, $i + 1), $company);
        }

        $manager->flush();
    }
}
