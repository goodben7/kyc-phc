<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806110517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent ADD site_id VARCHAR(16) DEFAULT NULL, ADD category_id VARCHAR(16) DEFAULT NULL, ADD function_title_id VARCHAR(16) DEFAULT NULL, ADD affected_location_id VARCHAR(16) DEFAULT NULL, ADD contractual_net_pay_usd VARCHAR(255) DEFAULT NULL, ADD contractual_net_pay_cdf VARCHAR(255) DEFAULT NULL, ADD date_hire DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD contrat_type VARCHAR(10) DEFAULT NULL, ADD end_contract_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD annotation VARCHAR(255) DEFAULT NULL, ADD place_birth VARCHAR(255) DEFAULT NULL, ADD social_security_id VARCHAR(255) DEFAULT NULL, ADD tax_id_number VARCHAR(255) DEFAULT NULL, ADD bank_account_id VARCHAR(255) DEFAULT NULL, ADD dependent INT DEFAULT NULL, ADD emergency_contact_person VARCHAR(255) DEFAULT NULL, ADD fact_sheet TINYINT(1) DEFAULT NULL, ADD onem_validated_contract TINYINT(1) DEFAULT NULL, ADD birth_certificate TINYINT(1) DEFAULT NULL, ADD marriage_license TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DE6124815 FOREIGN KEY (function_title_id) REFERENCES function_title (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DA4A28C87 FOREIGN KEY (affected_location_id) REFERENCES affected_location (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9DF6BD1646 ON agent (site_id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D12469DE2 ON agent (category_id)');
        $this->addSql('CREATE INDEX IDX_268B9C9DE6124815 ON agent (function_title_id)');
        $this->addSql('CREATE INDEX IDX_268B9C9DA4A28C87 ON agent (affected_location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DF6BD1646');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D12469DE2');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DE6124815');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DA4A28C87');
        $this->addSql('DROP INDEX IDX_268B9C9DF6BD1646 ON agent');
        $this->addSql('DROP INDEX IDX_268B9C9D12469DE2 ON agent');
        $this->addSql('DROP INDEX IDX_268B9C9DE6124815 ON agent');
        $this->addSql('DROP INDEX IDX_268B9C9DA4A28C87 ON agent');
        $this->addSql('ALTER TABLE agent DROP site_id, DROP category_id, DROP function_title_id, DROP affected_location_id, DROP contractual_net_pay_usd, DROP contractual_net_pay_cdf, DROP date_hire, DROP contrat_type, DROP end_contract_date, DROP annotation, DROP place_birth, DROP social_security_id, DROP tax_id_number, DROP bank_account_id, DROP dependent, DROP emergency_contact_person, DROP fact_sheet, DROP onem_validated_contract, DROP birth_certificate, DROP marriage_license');
    }
}
