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
    const COMPANY_ARRAY = [
        "Microsoft Corporation",
        "Apple Inc.",
        "Google LLC", // filiale d'Alphabet Inc.
        "Amazon.com Inc.",
        "Dell Technologies Inc.",
        "HP Inc.", // Hewlett-Packard
        "IBM", // International Business Machines Corporation
        "Intel Corporation",
        "Cisco Systems Inc.",
        "Oracle Corporation",
        "Adobe Inc.",
        "NVIDIA Corporation",
        "Samsung Electronics Co., Ltd.",
        "Sony Corporation",
        "Lenovo Group Limited",
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::COMPANY_COUNT_REFERENCE; ++$i) {
            $company = new Company();
            $company->setCompanyName($faker->name);
            $company->setAddress($faker->address);
            $company->setEmail($faker->email);
            $company->setSiretNumber($faker->numerify(str_repeat('#', 14)));


            $manager->persist($company);

            $this->addReference(sprintf('%s%d', self::COMPANY_REFERENCE, $i + 1), $company);
        }

        $manager->flush();
    }
}
