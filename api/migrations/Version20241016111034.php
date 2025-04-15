<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016111034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE division (id VARCHAR(16) NOT NULL, site_id VARCHAR(16) NOT NULL, label VARCHAR(120) NOT NULL, description VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, actived TINYINT(1) NOT NULL, INDEX IDX_10174714F6BD1646 (site_id), UNIQUE INDEX UNIQ_IDENTIFIER_CODE (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE division ADD CONSTRAINT FK_10174714F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE division DROP FOREIGN KEY FK_10174714F6BD1646');
        $this->addSql('DROP TABLE division');
    }
}
