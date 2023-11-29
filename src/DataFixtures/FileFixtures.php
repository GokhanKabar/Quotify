<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\File;

class FileFixtures
{
    const FILE_ARRAY = [
        'efdfb9c54f5b6db41f90d56982852054470925f8.pdf',
    ];
    
    public function createFileFromArray(array $array)
    {
        $faker = Factory::create('fr_FR');
        $file = new File();

        $file->setExtension($faker->fileExtension());
        $file->setPath('documents/' . $array[rand(0, count($array) - 1)]);
        $file->setSize($faker->randomNumber());
        $file->setType($faker->mimeType());
        $file->setName($faker->name());

        return $file;
    }

    public function load()
    {
    }
}
