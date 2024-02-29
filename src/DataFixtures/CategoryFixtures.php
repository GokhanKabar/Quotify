<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    const CATEGORY_REFERENCE = 'category';
    const CATEGORY_ARRAY = [
        "Ordinateurs portables",
        "Ordinateurs de bureau",
        "Tablettes tactiles",
        "Smartphones",
        "Imprimantes",
        "Disques durs externes",
        "Clés USB",
        "Écrans d'ordinateur",
        "Cartes graphiques",
        "Mémoires RAM",
        "Processeurs",
        "Souris et claviers sans fil",
        "Webcams",
        "Casques et écouteurs",
        "Chaises de bureau",
        "Claviers mécaniques",
        "Tapis de souris",
        "Lampes de bureau",
        "Supports pour moniteurs",
        "Organisateurs de bureau",
    ];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(self::CATEGORY_ARRAY); ++$i) {
            $category = new Category();
            $category->setCategoryName(self::CATEGORY_ARRAY[$i]);

            $manager->persist($category);

            $this->addReference(sprintf('%s%d', self::CATEGORY_REFERENCE, $i + 1), $category);
        }

        $manager->flush();
    }
}
