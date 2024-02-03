<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109173322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listings CHANGE title title VARCHAR(50) DEFAULT NULL, CHANGE description description VARCHAR(50) DEFAULT NULL, CHANGE status status ENUM(\'draft\', \'pending\', \'approved\') DEFAULT NULL COMMENT \'(DC2Type:listing_status_enum)\', CHANGE country country VARCHAR(50) DEFAULT NULL, CHANGE city city VARCHAR(50) DEFAULT NULL, CHANGE zip_code zip_code INT DEFAULT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE street_number street_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE structure_types CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE icon_url icon_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listings CHANGE title title VARCHAR(50) NOT NULL, CHANGE description description VARCHAR(50) NOT NULL, CHANGE status status ENUM(\'draft\', \'pending\', \'approved\') NOT NULL COMMENT \'(DC2Type:listing_status_enum)\', CHANGE country country VARCHAR(50) NOT NULL, CHANGE city city VARCHAR(50) NOT NULL, CHANGE zip_code zip_code INT NOT NULL, CHANGE street street VARCHAR(255) NOT NULL, CHANGE street_number street_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE structure_types CHANGE name name VARCHAR(255) NOT NULL, CHANGE icon_url icon_url VARCHAR(255) NOT NULL');
    }
}
