<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;

class UserFixtures extends Fixture
{
    const USER_REFERENCE = 'user';
    const USER_COUNT_REFERENCE = 10;
    const USER_PLAIN_PASSWORD = 'password123';
    const USER_ROLES = [
        'ROLE_ADMIN',
        'ROLE_COMPANY',
        'ROLE_ACCOUNTANT',
    ];
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
        "Paris",
        "Marseille",
        "Lyon",
        "Toulouse",
        "Nice"
    ];
    
    const USER_FIRSTNAME = [
        "Jean",
        "Pierre",
        "Paul",
        "Jacques",
        "Marie",
        "Claire",
        "Sophie",
        "Julie",
        "Nicolas",
        "Alexandre"
    ];
    
    const USER_LASTNAME = [
        "Dupont",
        "Durand",
        "Martin",
        "Bernard",
        "Dubois",
        "Thomas",
        "Robert",
        "Richard",
        "Petit",
        "Moreau"
    ];
    
    const USER_EMAIL = [
        "jean.dupont@example.com",
        "pierre.durand@example.com",
        "paul.martin@example.com",
        "jacques.bernard@example.com",
        "marie.dubois@example.com",
        "claire.thomas@example.com",
        "sophie.robert@example.com",
        "julie.richard@example.com",
        "nicolas.petit@example.com",
        "alexandre.moreau@example.com"
    ];
    
    const USER_ADDRESS = [
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
            "Lyon" => "69002",
            "Nice" => "06000",
        ];
    
        $roleCount = [
            'ROLE_ADMIN' => 0,
            'ROLE_ACCOUNTANT' => 0,
            'ROLE_COMPANY' => 0,
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
    
            // ici on assigne les rôles (3 admins, 3 comptables et 4 entreprises)
            if ($i < 3) {
                $role = 'ROLE_ADMIN';
            } elseif ($i < 6) {
                $role = 'ROLE_ACCOUNTANT';
            } else {
                $role = 'ROLE_COMPANY';
            }

            if ($roleCount[$role] < 3) {
                $user->setRoles([$role]);
                $roleCount[$role]++;
            } else {
                $user->setRoles(['ROLE_COMPANY']);
            }
    
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setUpdatedAt(new DateTimeImmutable());
    
            if ($user->getRoles() === ['ROLE_COMPANY'] || $user->getRoles() === ['ROLE_ACCOUNTANT']) {
                $user->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE . rand(1, CompanyFixtures::COMPANY_COUNT_REFERENCE)));
            }
    
            $manager->persist($user);
            $manager->persist($superAdmin);
    
            $this->addReference(sprintf('%s%d', self::USER_REFERENCE, $i + 1), $user);
        }
    
        $manager->flush();
    }    
}
