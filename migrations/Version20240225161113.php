<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225161113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD image_size INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ADD phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD gender VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP phone_number');
        $this->addSql('ALTER TABLE "user" DROP address');
        $this->addSql('ALTER TABLE "user" DROP city');
        $this->addSql('ALTER TABLE "user" DROP postal_code');
        $this->addSql('ALTER TABLE "user" DROP gender');
        $this->addSql('ALTER TABLE product DROP updated_at');
        $this->addSql('ALTER TABLE product DROP image_name');
        $this->addSql('ALTER TABLE product DROP image_size');
    }
}
