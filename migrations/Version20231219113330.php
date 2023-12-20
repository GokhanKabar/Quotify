<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219113330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_file (invoice_id INT NOT NULL, file_id INT NOT NULL, PRIMARY KEY(invoice_id, file_id))');
        $this->addSql('CREATE INDEX IDX_8E5A54752989F1FD ON invoice_file (invoice_id)');
        $this->addSql('CREATE INDEX IDX_8E5A547593CB796C ON invoice_file (file_id)');
        $this->addSql('CREATE TABLE quotation_file (quotation_id INT NOT NULL, file_id INT NOT NULL, PRIMARY KEY(quotation_id, file_id))');
        $this->addSql('CREATE INDEX IDX_116B935CB4EA4E60 ON quotation_file (quotation_id)');
        $this->addSql('CREATE INDEX IDX_116B935C93CB796C ON quotation_file (file_id)');
        $this->addSql('ALTER TABLE invoice_file ADD CONSTRAINT FK_8E5A54752989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_file ADD CONSTRAINT FK_8E5A547593CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation_file ADD CONSTRAINT FK_116B935CB4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quotation_file ADD CONSTRAINT FK_116B935C93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk_9065174493cb796c');
        $this->addSql('DROP INDEX uniq_9065174493cb796c');
        $this->addSql('ALTER TABLE invoice DROP file_id');
        $this->addSql('ALTER TABLE product ADD user_reference_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9689A2AB FOREIGN KEY (user_reference_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04AD9689A2AB ON product (user_reference_id)');
        $this->addSql('ALTER TABLE quotation DROP CONSTRAINT fk_474a8db993cb796c');
        $this->addSql('DROP INDEX uniq_474a8db993cb796c');
        $this->addSql('ALTER TABLE quotation DROP file_id');
        $this->addSql('ALTER TABLE "user" ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP address');
        $this->addSql('ALTER TABLE "user" DROP number_phone');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice_file DROP CONSTRAINT FK_8E5A54752989F1FD');
        $this->addSql('ALTER TABLE invoice_file DROP CONSTRAINT FK_8E5A547593CB796C');
        $this->addSql('ALTER TABLE quotation_file DROP CONSTRAINT FK_116B935CB4EA4E60');
        $this->addSql('ALTER TABLE quotation_file DROP CONSTRAINT FK_116B935C93CB796C');
        $this->addSql('DROP TABLE invoice_file');
        $this->addSql('DROP TABLE quotation_file');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6');
        $this->addSql('DROP INDEX IDX_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE "user" ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD number_phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP company_id');
        $this->addSql('ALTER TABLE invoice ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk_9065174493cb796c FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_9065174493cb796c ON invoice (file_id)');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD9689A2AB');
        $this->addSql('DROP INDEX IDX_D34A04AD9689A2AB');
        $this->addSql('ALTER TABLE product DROP user_reference_id');
        $this->addSql('ALTER TABLE quotation ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT fk_474a8db993cb796c FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_474a8db993cb796c ON quotation (file_id)');
    }
}
