<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    const PRODUCT_REFERENCE = 'product';
    const PRODUCT_COUNT_REFERENCE = 10;
    const PRODUCT_ARRAY = [
        "Ordinateurs portables" => ["MacBook Air", "Dell XPS 13", "HP Spectre x360", "Lenovo ThinkPad X1 Carbon"],
        "Ordinateurs de bureau" => ["Dell Inspiron Desktop", "HP Pavilion Desktop", "Lenovo IdeaCentre"],
        "Tablettes tactiles" => ["iPad Pro", "Samsung Galaxy Tab S7", "Microsoft Surface Pro"],
        "Smartphones" => ["iPhone 13", "Samsung Galaxy S21", "Google Pixel 6", "OnePlus 9"],
        "Imprimantes" => ["HP OfficeJet Pro", "Canon PIXMA", "Epson EcoTank", "Brother HL-L2350DW"],
        "Disques durs externes" => ["Seagate Backup Plus Portable", "Western Digital My Passport", "Samsung T5 Portable SSD"],
        "Clés USB" => ["SanDisk Ultra Flair", "Kingston DataTraveler", "Samsung BAR Plus"],
        "Écrans d'ordinateur" => ["Dell UltraSharp U2720Q", "ASUS ProArt Display", "LG UltraFine"],
        "Cartes graphiques" => ["NVIDIA GeForce RTX 3080", "AMD Radeon RX 6900 XT"],
        "Mémoires RAM" => ["Corsair Vengeance LPX", "G.Skill Ripjaws V", "Kingston HyperX Fury"],
        "Processeurs" => ["Intel Core i9-12900K", "AMD Ryzen 9 5900X"],
        "Souris et claviers sans fil" => ["Logitech MX Master 3", "Apple Magic Keyboard", "Microsoft Surface Arc Mouse"],
        "Webcams" => ["Logitech C920 HD Pro", "Razer Kiyo", "Microsoft LifeCam HD-3000"],
        "Casques et écouteurs" => ["Sony WH-1000XM4", "AirPods Pro", "Bose QuietComfort 45", "Sennheiser HD 660S"],
        "Chaises de bureau" => ["Herman Miller Aeron", "Steelcase Series 1", "Corsair T3 Rush", "IKEA Markus"],
        "Claviers mécaniques" => ["Corsair K95 RGB Platinum", "Razer BlackWidow Elite", "SteelSeries Apex Pro"],
        "Tapis de souris" => ["SteelSeries QcK+", "Corsair MM300", "Razer Goliathus"],
        "Lampes de bureau" => ["BenQ e-Reading LED Desk Lamp", "Taotronics LED Desk Lamp", "Lume Cube Air VC"],
        "Supports pour moniteurs" => ["AmazonBasics Premium Single Monitor Stand", "VIVO Dual LCD Monitor Desk Mount"],
        "Organisateurs de bureau" => ["SimpleHouseware Mesh Desk Organizer", "Greenco Mesh Office Supplies Desk Organizer"],
    ];

    const PRODUCT_DESCRIPTION = [];

    public function load(ObjectManager $manager): void
    {
        $productCount = 0;

        foreach (self::PRODUCT_ARRAY as $categoryName => $products) {
            $categoryIndex = array_search($categoryName, CategoryFixtures::CATEGORY_ARRAY);
            $categoryReference = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE . ($categoryIndex + 1));

            foreach ($products as $index => $productName) {
                $product = new Product();
                $product->setProductName($productName);
                $product->setDescription($this->getProductDescription($categoryName));
                $product->setUnitPrice(rand(10, 1000));
                $product->setCategory($categoryReference);
                $product->setCompanyReference($this->getReference(CompanyFixtures::COMPANY_REFERENCE . rand(1, CompanyFixtures::COMPANY_COUNT_REFERENCE)));

                $manager->persist($product);

                $this->addReference(self::PRODUCT_REFERENCE . (++$productCount), $product);
            }
        }

        $manager->flush();
    }

    private function getProductDescription(string $categoryName): string
    {
        switch ($categoryName) {
            case "Ordinateurs portables":
                return "Des ordinateurs portables légers et puissants, parfaitement adaptés pour les professionnels et les étudiants en déplacement.";
            case "Ordinateurs de bureau":
                return "Des ordinateurs de bureau de haute performance pour une utilisation professionnelle, gaming et quotidienne, offrant une grande extensibilité.";
            case "Tablettes tactiles":
                return "Tablettes haute résolution, idéales pour la lecture, le surf sur internet, et l'utilisation multimédia, avec une autonomie prolongée.";
            case "Smartphones":
                return "Dernière génération de smartphones combinant design élégant et technologie de pointe pour rester connecté en tout lieu.";
            case "Imprimantes":
                return "Imprimantes multifonctions fiables et efficaces, parfaites pour les documents de tous les jours comme pour les photos de qualité professionnelle.";
            case "Disques durs externes":
                return "Solutions de stockage portables offrant rapidité et capacité pour sauvegarder et partager vos données en toute sécurité.";
            case "Clés USB":
                return "Stockage mobile et rapide pour vos données, idéal pour transférer et sécuriser vos fichiers entre différents appareils.";
            case "Écrans d'ordinateur":
                return "Écrans offrant une qualité d'image exceptionnelle pour les professionnels créatifs, les joueurs et les cinéphiles.";
            case "Cartes graphiques":
                return "Cartes graphiques puissantes pour gaming, création de contenus et applications de réalité virtuelle, garantissant des performances et une immersion totale.";
            case "Mémoires RAM":
                return "Modules de mémoire pour booster les performances de votre système, essentiels pour le multitâche et les applications gourmandes en ressources.";
            case "Processeurs":
                return "Processeurs de pointe offrant des performances exceptionnelles pour le gaming, la création de contenu et les applications professionnelles.";
            case "Souris et claviers sans fil":
                return "Accessoires ergonomiques et sans fil pour un espace de travail épuré et une productivité optimisée.";
            case "Webcams":
                return "Webcams HD pour des appels vidéo clairs et professionnels, idéales pour les conférences web et les diffusions en direct.";
            case "Casques et écouteurs":
                return "Audio de haute qualité pour une immersion sonore complète, que ce soit pour la musique, le gaming ou les appels.";
            case "Chaises de bureau":
                return "Chaises de bureau ergonomiques conçues pour un confort optimal et un soutien lombaire, essentielles pour de longues heures de travail.";
            case "Claviers mécaniques":
                return "Claviers mécaniques offrant précision, durabilité et réactivité, pour une expérience de frappe supérieure.";
            case "Tapis de souris":
                return "Tapis de souris conçus pour le gaming et le travail professionnel, offrant une surface optimale pour tous types de souris.";
            case "Lampes de bureau":
                return "Lampes de bureau LED ajustables pour un éclairage optimal de votre espace de travail, réduisant la fatigue oculaire.";
            case "Supports pour moniteurs":
                return "Supports pour moniteurs permettant d'ajuster l'angle et la hauteur de votre écran, améliorant l'ergonomie et l'espace sur le bureau.";
            case "Organisateurs de bureau":
                return "Solutions d'organisation de bureau pour maintenir un espace de travail ordonné, améliorant l'efficacité et la productivité.";
            default:
                return "Description par défaut pour " . $categoryName;
        }
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
