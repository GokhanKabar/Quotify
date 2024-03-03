<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use App\Entity\Company;

class UserFixtures extends Fixture
{
    const USER_REFERENCE = 'user';
    const USER_COUNT_REFERENCE = 4;
    const USER_PLAIN_PASSWORD = 'password123';
    const GENDER = [
        'M',
        'F',
    ];

    const USER_CITY = [
        "Paris",
        "Paris",
        "Toulouse",
        "Bordeaux",
        "Nancy",
    ];
    
    const USER_FIRSTNAME = [
        "Jean",
        "Pierre",
        "Paul",
        "Jacques",
        "Marie",
    ];
    
    const USER_LASTNAME = [
        "Dupont",
        "Durand",
        "Martin",
        "Bernard",
        "Dubois",
    ];
    
    const USER_EMAIL = [
        "jean.dupont@example.com",
        "pierre.durand@example.com",
        "paul.martin@example.com",
        "jacques.bernard@example.com",
        "marie.dubois@example.com",
    ];
    
    const USER_ADDRESS = [
        "10 Rue de la Paix, 75002 Paris",
        "5 Avenue Anatole France, 75007 Paris",
        "32 Boulevard de Strasbourg, 31000 Toulouse",
        "15 Rue Sainte-Catherine, 33000 Bordeaux",
        "7 Place Stanislas, 54000 Nancy",
    ];

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $postalCodes = [
            "Paris" => "75000",
            "Toulouse" => "31000",
            "Bordeaux" => "33000",
            "Nancy" => "54000",
            "Marseille" => "13001",
        ];

        $superAdmin = new User();
        $superAdmin->setEmail('admin@quotify.fr');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $superAdmin,
            self::USER_PLAIN_PASSWORD
        );
        $superAdmin->setPassword($hashedPassword);
        $superAdmin->setFirstName('admin');
        $superAdmin->setLastName('admin');
        $superAdmin->setPhoneNumber('01 23 45 67 89');
        $superAdmin->setAddress('10 Rue de la Paix');
        $superAdmin->setCity('Paris');
        $superAdmin->setPostalCode('75000');
        $superAdmin->setGender('M');
        $superAdmin->setRoles(['ROLE_ADMIN']);
        $superAdmin->setCreatedAt(new DateTimeImmutable());
        $superAdmin->setUpdatedAt(new DateTimeImmutable());

        $quotify = new Company();
        $quotify->setCompanyName('Quotify');
        $quotify->setAddress('10 Rue de la Paix, 75002 Paris');
        $quotify->setEmail('quotify@quotify.fr');
        $quotify->setSiretNumber(rand(10000000000000, 99999999999999));

        $manager->persist($quotify);

        $superAdmin->setCompany($quotify);

        $manager->persist($superAdmin);
    
        for ($i = 0; $i < self::USER_COUNT_REFERENCE; ++$i) {
            $user = new User();
            $user->setEmail(self::USER_EMAIL[$i]);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                self::USER_PLAIN_PASSWORD
            );
            $user->setPassword($hashedPassword);
            $user->setFirstName(self::USER_FIRSTNAME[$i]);
            $user->setLastName(self::USER_LASTNAME[$i]);
            $user->setPhoneNumber(sprintf("0%d %d%d %d%d %d%d %d%d", rand(1, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9)));
            $user->setAddress(self::USER_ADDRESS[$i]);
            $user->setCity(self::USER_CITY[$i]);
            $user->setPostalCode($postalCodes[self::USER_CITY[$i]]);
            $user->setGender(self::GENDER[rand(0, 1)]);

            if ($i < 2) {
                $role = 'ROLE_ACCOUNTANT';
            } else {
                $role = 'ROLE_COMPANY';
            }

            $user->setRoles([$role]);
    
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setUpdatedAt(new DateTimeImmutable());
    
            $user->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE . rand(1, CompanyFixtures::COMPANY_COUNT_REFERENCE)));
    
            $manager->persist($user);
    
            $this->addReference(sprintf('%s%d', self::USER_REFERENCE, $i + 1), $user);
        }
    
        $manager->flush();
    }    
}
