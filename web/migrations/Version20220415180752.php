<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415180752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, sku VARCHAR(255) DEFAULT NULL, ean13 VARCHAR(255) DEFAULT NULL, ean_virtual VARCHAR(255) DEFAULT NULL, eans LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', stock INT DEFAULT NULL, stock_catalog INT DEFAULT NULL, stock_to_show INT DEFAULT NULL, stock_available INT DEFAULT NULL, category_name VARCHAR(255) DEFAULT NULL, brand_name VARCHAR(255) DEFAULT NULL, part_number VARCHAR(255) DEFAULT NULL, collection VARCHAR(255) DEFAULT NULL, price_catalog DOUBLE PRECISION DEFAULT NULL, price_wholesale DOUBLE PRECISION DEFAULT NULL, price_retail DOUBLE PRECISION DEFAULT NULL, pvp DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, weight_packaging DOUBLE PRECISION DEFAULT NULL, height_packaging DOUBLE PRECISION DEFAULT NULL, width_packaging DOUBLE PRECISION DEFAULT NULL, length_packaging DOUBLE PRECISION DEFAULT NULL, weight_master DOUBLE PRECISION DEFAULT NULL, height_master DOUBLE PRECISION DEFAULT NULL, width_master DOUBLE PRECISION DEFAULT NULL, length_master DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, product_lang_supplier LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', product_images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', product_attributes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
    }
}
