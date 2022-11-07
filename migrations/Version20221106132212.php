<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106132212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', seller_id_id INT NOT NULL, region_id_id INT NOT NULL, sale_id_id INT DEFAULT NULL, product_id_id INT NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, customer_full_name VARCHAR(255) NOT NULL, INDEX IDX_4C62E638DF4C85EA (seller_id_id), INDEX IDX_4C62E638C7209D4F (region_id_id), UNIQUE INDEX UNIQ_4C62E638AF98C6D4 (sale_id_id), INDEX IDX_4C62E638DE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country_code VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, net_amount NUMERIC(10, 4) NOT NULL, gross_amount NUMERIC(10, 4) NOT NULL, tax_rate NUMERIC(5, 4) NOT NULL, cost NUMERIC(10, 4) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, date_joined DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638DF4C85EA FOREIGN KEY (seller_id_id) REFERENCES seller (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638C7209D4F FOREIGN KEY (region_id_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638AF98C6D4 FOREIGN KEY (sale_id_id) REFERENCES sale (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638DE18E50B');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638C7209D4F');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638AF98C6D4');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638DF4C85EA');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE seller');
    }
}
