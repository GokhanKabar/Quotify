<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228203241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_detail ADD tva DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_size INT DEFAULT NULL, DROP tva');
        $this->addSql('ALTER TABLE quotation_detail ADD tva DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user ADD phone_number VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(255) DEFAULT NULL, ADD gender VARCHAR(255) DEFAULT NULL, DROP is_verified');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_detail DROP tva');
        $this->addSql('ALTER TABLE product ADD tva DOUBLE PRECISION NOT NULL, DROP updated_at, DROP image_name, DROP image_size');
        $this->addSql('ALTER TABLE quotation_detail DROP tva');
        $this->addSql('ALTER TABLE `user` ADD is_verified TINYINT(1) NOT NULL, DROP phone_number, DROP address, DROP city, DROP postal_code, DROP gender');
    }
}
