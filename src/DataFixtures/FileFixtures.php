<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\File;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FileFixtures extends Fixture implements DependentFixtureInterface
{
    const FILE_REFERENCE = 'file';
    const FILE_COUNT_REFERENCE = 10;
    const FILE_ARRAY = [
        'fileFixturesExample.pdf',
    ]; 
    
    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::FILE_COUNT_REFERENCE; ++$i) {
            $file = new File();

            $file->setExtension($faker->fileExtension());
            $file->setPath('documents/' . self::FILE_ARRAY[rand(0, count(self::FILE_ARRAY) - 1)]);
            $file->setSize($faker->randomNumber());
            $file->setType($faker->mimeType());
            $file->setName($faker->name());

            $manager->persist($file);

            $this->addReference(sprintf('%s%d', self::FILE_REFERENCE, $i + 1), $file);
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
