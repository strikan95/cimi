<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222155248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD sub VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD username VARCHAR(255) NOT NULL, ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, DROP user_identity_sub, DROP user_identity_email, DROP user_identity_username, DROP user_details_first_name, DROP user_details_last_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_identity_sub VARCHAR(255) NOT NULL, ADD user_identity_email VARCHAR(255) NOT NULL, ADD user_identity_username VARCHAR(255) NOT NULL, ADD user_details_first_name VARCHAR(255) DEFAULT NULL, ADD user_details_last_name VARCHAR(255) DEFAULT NULL, DROP sub, DROP email, DROP username, DROP first_name, DROP last_name');
    }
}
