<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;

class UserFixtures extends Fixture
{
    const USER_REFERENCE = 'user';
    const USER_COUNT_REFERENCE = 10;
    const USER_PLAIN_PASSWORD = 'password123';
    const USER_ROLES = [
        'ROLE_USER',
        'ROLE_ADMIN',
        'ROLE_COMPANY',
        'ROLE_ACCOUNTANT',
    ];
    const GENDER = [
        'M',
        'F',
    ];
    
    const DEFAULT_LANGUAGE = 'fr_FR';

    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function getRandomElements(array $array, int $count): array
    {
        shuffle($array);
        $randomElements = array_slice($array, 0, $count);
        return $randomElements;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = $this->faker;

        for ($i = 0; $i < self::USER_COUNT_REFERENCE; ++$i) {
            $user = new User();
            $user->setEmail(($faker->email));
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                self::USER_PLAIN_PASSWORD
            );
            $user->setPassword($hashedPassword);
            $user->setFirstName($faker->firstName(self::DEFAULT_LANGUAGE));
            $user->setLastName($faker->lastName(self::DEFAULT_LANGUAGE));
            $phoneNumber = preg_replace(
                '/\s+/',
                '',
                str_replace(['+33', '(0)', ' '], ['0', '', ''], $faker->phoneNumber(self::DEFAULT_LANGUAGE))
            );
            $user->setPhoneNumber($phoneNumber);
            $user->setAddress($faker->streetAddress(self::DEFAULT_LANGUAGE));
            $user->setCity($faker->city(self::DEFAULT_LANGUAGE));
            $user->setPostalCode($faker->postcode(self::DEFAULT_LANGUAGE));
            $user->setGender(self::GENDER[rand(0, 1)]);
            $user->setRoles($this->getRandomElements(self::USER_ROLES, rand(1, count(self::USER_ROLES))));
            $user->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()));
            $user->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()));
            $user->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE . rand(1, CompanyFixtures::COMPANY_COUNT_REFERENCE)));

            $manager->persist($user);

            $this->addReference(sprintf('%s%d', self::USER_REFERENCE, $i + 1), $user);
        }

        $manager->flush();
    }
}
