<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809181438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE import (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type VARCHAR(30) NOT NULL, method VARCHAR(15) NOT NULL, status VARCHAR(1) NOT NULL, created_by VARCHAR(16) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', message VARCHAR(255) DEFAULT NULL, loaded INT NOT NULL, treated INT NOT NULL, data1 VARCHAR(255) DEFAULT NULL, data2 VARCHAR(255) DEFAULT NULL, data3 VARCHAR(255) DEFAULT NULL, data4 VARCHAR(255) DEFAULT NULL, data5 VARCHAR(255) DEFAULT NULL, data6 VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE import');
    }
}
