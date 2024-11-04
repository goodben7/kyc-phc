<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104101330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent CHANGE country country VARCHAR(2) DEFAULT NULL, CHANGE birthday birthday DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE marital_status marital_status VARCHAR(1) DEFAULT NULL, CHANGE gender gender VARCHAR(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent CHANGE country country VARCHAR(2) NOT NULL, CHANGE birthday birthday DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE marital_status marital_status VARCHAR(1) NOT NULL, CHANGE gender gender VARCHAR(1) NOT NULL');
    }
}
