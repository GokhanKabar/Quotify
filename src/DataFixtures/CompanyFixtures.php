<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;

class CompanyFixtures extends Fixture
{
    const COMPANY_COUNT_REFERENCE = 20;
    const COMPANY_REFERENCE = 'company';
    const COMPANY_ARRAY = [
        "Microsoft Corporation",
        "Apple Inc.",
        "Google LLC",
        "Amazon.com Inc.",
        "Dell Technologies Inc.",
        "HP Inc.",
        "IBM",
        "Intel Corporation",
        "Cisco Systems Inc.",
        "Oracle Corporation",
        "Adobe Inc.",
        "NVIDIA Corporation",
        "Samsung Electronics Co., Ltd.",
        "Sony Corporation",
        "Lenovo Group Limited",
        "ASUS",
        "Acer Inc.",
        "Toshiba Corporation",
        "Fujitsu Limited",
        "Panasonic Corporation"
    ];

    const COMPANY_EMAIL = [
        "info@microsoft.com",
        "contact@apple.com",
        "support@google.com",
        "info@amazon.com",
        "contact@dell.com",
        "support@hp.com",
        "info@ibm.com",
        "contact@intel.com",
        "support@cisco.com",
        "info@oracle.com",
        "contact@adobe.com",
        "support@nvidia.com",
        "info@samsung.com",
        "contact@sony.com",
        "info@lenovo.com",
        "info@asus.com",
        "contact@acer.com",
        "info@toshiba.com",
        "support@fujitsu.com",
        "info@panasonic.com"
    ];    

    const COMPANY_ADDRESS = [
        "10 Rue de la Paix, 75002 Paris",
        "5 Avenue Anatole France, 75007 Paris",
        "32 Boulevard de Strasbourg, 31000 Toulouse",
        "15 Rue Sainte-Catherine, 33000 Bordeaux",
        "7 Place Stanislas, 54000 Nancy",
        "2 Rue des Pyramides, 75001 Paris",
        "45 La Canebière, 13001 Marseille",
        "8 Rue Victor Hugo, 69002 Lyon",
        "3 Place du Capitole, 31000 Toulouse",
        "22 Avenue Jean Médecin, 06000 Nice",
        "14 Rue des Clercs, 38000 Grenoble",
        "11 Rue de l'Horloge, 22100 Dinan",
        "29 Grand'Rue, 67000 Strasbourg",
        "9 Rue Royale, 45000 Orléans",
        "17 Cours Mirabeau, 13100 Aix-en-Provence",
        "4 Quai de la Douane, 29200 Brest",
        "38 Rue Saint-Michel, 14000 Caen",
        "27 Rue du Faubourg Montmartre, 75009 Paris",
        "12 Rue de la République, 13001 Marseille",
        "6 Boulevard Montmartre, 75009 Paris"
    ];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COMPANY_COUNT_REFERENCE; ++$i) {
            $company = new Company();
            $company->setCompanyName(self::COMPANY_ARRAY[$i]);
            $company->setAddress(self::COMPANY_ADDRESS[$i]);
            $company->setEmail(self::COMPANY_EMAIL[$i]);
            $company->setSiretNumber(rand(10000000000000, 99999999999999));

            $manager->persist($company);

            $this->addReference(sprintf('%s%d', self::COMPANY_REFERENCE, $i + 1), $company);
        }

        $manager->flush();
    }
}
