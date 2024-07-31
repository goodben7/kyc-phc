<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731155037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id VARCHAR(16) NOT NULL, first_name VARCHAR(30) NOT NULL, last_name VARCHAR(30) NOT NULL, post_name VARCHAR(30) NOT NULL, full_name VARCHAR(120) NOT NULL, country VARCHAR(2) NOT NULL, birthday DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', kyc_status VARCHAR(1) NOT NULL, marital_status VARCHAR(1) NOT NULL, gender VARCHAR(1) NOT NULL, status VARCHAR(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', external_reference_id VARCHAR(255) DEFAULT NULL, created_by VARCHAR(16) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(16) DEFAULT NULL, identification_number VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_IDENTIFICATION_NUMBER (identification_number), UNIQUE INDEX UNIQ_IDENTIFIER_EXTERNAL_REFERENCE_ID (external_reference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agent');
    }
}
