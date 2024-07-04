<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626143803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AD4619D1A');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AD4619D1A FOREIGN KEY (listing_id) REFERENCES listings (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AD4619D1A');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AD4619D1A FOREIGN KEY (listing_id) REFERENCES listings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
