<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306153540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, icon_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id VARCHAR(255) NOT NULL, listing_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, thumbnail_url VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6AD4619D1A (listing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listings (id INT AUTO_INCREMENT NOT NULL, structure_type_id INT DEFAULT NULL, place_type_id INT DEFAULT NULL, host_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status ENUM(\'draft\', \'pending\', \'approved\') NOT NULL COMMENT \'(DC2Type:listing_status_enum)\', last_updated_step VARCHAR(255) NOT NULL, cover_image_url VARCHAR(255) NOT NULL, price INT NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, post_code VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, street_number VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, coordinates GEOMETRY NOT NULL, INDEX IDX_9A7BD98E1EEEFCA2 (structure_type_id), INDEX IDX_9A7BD98EF1809B68 (place_type_id), INDEX IDX_9A7BD98E1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listing_amenity (listing_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_B45E022CD4619D1A (listing_id), INDEX IDX_B45E022C9F9F1305 (amenity_id), PRIMARY KEY(listing_id, amenity_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, icon_url VARCHAR(256) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rent_periods (id INT AUTO_INCREMENT NOT NULL, listing_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_863BE3D2D4619D1A (listing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, icon_url VARCHAR(256) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, sub VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AD4619D1A FOREIGN KEY (listing_id) REFERENCES listings (id)');
        $this->addSql('ALTER TABLE listings ADD CONSTRAINT FK_9A7BD98E1EEEFCA2 FOREIGN KEY (structure_type_id) REFERENCES structure_types (id)');
        $this->addSql('ALTER TABLE listings ADD CONSTRAINT FK_9A7BD98EF1809B68 FOREIGN KEY (place_type_id) REFERENCES place_types (id)');
        $this->addSql('ALTER TABLE listings ADD CONSTRAINT FK_9A7BD98E1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE listing_amenity ADD CONSTRAINT FK_B45E022CD4619D1A FOREIGN KEY (listing_id) REFERENCES listings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listing_amenity ADD CONSTRAINT FK_B45E022C9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rent_periods ADD CONSTRAINT FK_863BE3D2D4619D1A FOREIGN KEY (listing_id) REFERENCES listings (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AD4619D1A');
        $this->addSql('ALTER TABLE listings DROP FOREIGN KEY FK_9A7BD98E1EEEFCA2');
        $this->addSql('ALTER TABLE listings DROP FOREIGN KEY FK_9A7BD98EF1809B68');
        $this->addSql('ALTER TABLE listings DROP FOREIGN KEY FK_9A7BD98E1FB8D185');
        $this->addSql('ALTER TABLE listing_amenity DROP FOREIGN KEY FK_B45E022CD4619D1A');
        $this->addSql('ALTER TABLE listing_amenity DROP FOREIGN KEY FK_B45E022C9F9F1305');
        $this->addSql('ALTER TABLE rent_periods DROP FOREIGN KEY FK_863BE3D2D4619D1A');
        $this->addSql('DROP TABLE amenities');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE listings');
        $this->addSql('DROP TABLE listing_amenity');
        $this->addSql('DROP TABLE place_types');
        $this->addSql('DROP TABLE rent_periods');
        $this->addSql('DROP TABLE structure_types');
        $this->addSql('DROP TABLE user');
    }
}
