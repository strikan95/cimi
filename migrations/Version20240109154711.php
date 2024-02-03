<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109154711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE listings (id INT AUTO_INCREMENT NOT NULL, structure_type_id INT DEFAULT NULL, host_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(50) NOT NULL, status ENUM(\'draft\', \'pending\', \'approved\') NOT NULL COMMENT \'(DC2Type:listing_status_enum)\', country VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, zip_code INT NOT NULL, street VARCHAR(255) NOT NULL, street_number VARCHAR(255) NOT NULL, INDEX IDX_9A7BD98E1EEEFCA2 (structure_type_id), INDEX IDX_9A7BD98E1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon_url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, sub VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listings ADD CONSTRAINT FK_9A7BD98E1EEEFCA2 FOREIGN KEY (structure_type_id) REFERENCES structure_types (id)');
        $this->addSql('ALTER TABLE listings ADD CONSTRAINT FK_9A7BD98E1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listings DROP FOREIGN KEY FK_9A7BD98E1EEEFCA2');
        $this->addSql('ALTER TABLE listings DROP FOREIGN KEY FK_9A7BD98E1FB8D185');
        $this->addSql('DROP TABLE listings');
        $this->addSql('DROP TABLE structure_types');
        $this->addSql('DROP TABLE user');
    }
}
