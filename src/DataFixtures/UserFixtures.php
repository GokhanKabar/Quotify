<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const USER_REFERENCE = 'user';
    const USER_COUNT_REFERENCE = 10;
    const USER_PASSWORD = 'test';
    const USER_ROLES = [
        'ROLE_CLIENT',
        'ROLE_ADMIN',
        'ROLE_COMPANY',
        'ROLE_ACCOUNTANT',
    ];

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
            $plainPassword = 'test123';
            $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setAddress($faker->address);

            $phoneNumber = preg_replace(
                '/\s+/',
                '',
                str_replace(['+33', '(0)', ' '], ['0', '', ''], $faker->phoneNumber)
            );
            $user->setNumberPhone($phoneNumber);

            $user->setRoles($this->getRandomElements(self::USER_ROLES, rand(1, count(self::USER_ROLES))));

            $manager->persist($user);

            $this->addReference(sprintf('%s%d', self::USER_REFERENCE, $i + 1), $user);
        }

        $manager->flush();
    }
}
