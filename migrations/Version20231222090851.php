<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222090851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidate_company (candidate_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_C06EA4EE91BD8781 (candidate_id), INDEX IDX_C06EA4EE979B1AD6 (company_id), PRIMARY KEY(candidate_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_company ADD CONSTRAINT FK_C06EA4EE91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidate_company ADD CONSTRAINT FK_C06EA4EE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate_company DROP FOREIGN KEY FK_C06EA4EE91BD8781');
        $this->addSql('ALTER TABLE candidate_company DROP FOREIGN KEY FK_C06EA4EE979B1AD6');
        $this->addSql('DROP TABLE candidate_company');
    }
}
