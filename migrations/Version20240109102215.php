<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109102215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT NOT NULL, job_id INT NOT NULL, message LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A45BDDC15AEA4428 (curriculum_id), INDEX IDX_A45BDDC1BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_company (candidate_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_C06EA4EE91BD8781 (candidate_id), INDEX IDX_C06EA4EE979B1AD6 (company_id), PRIMARY KEY(candidate_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cvs (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5B9529D91BD8781 (candidate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_candidate (job_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_F026155BE04EA9 (job_id), INDEX IDX_F02615591BD8781 (candidate_id), PRIMARY KEY(job_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC15AEA4428 FOREIGN KEY (curriculum_id) REFERENCES cvs (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE candidate_company ADD CONSTRAINT FK_C06EA4EE91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidate_company ADD CONSTRAINT FK_C06EA4EE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cvs ADD CONSTRAINT FK_5B9529D91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE job_candidate ADD CONSTRAINT FK_F026155BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_candidate ADD CONSTRAINT FK_F02615591BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC15AEA4428');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1BE04EA9');
        $this->addSql('ALTER TABLE candidate_company DROP FOREIGN KEY FK_C06EA4EE91BD8781');
        $this->addSql('ALTER TABLE candidate_company DROP FOREIGN KEY FK_C06EA4EE979B1AD6');
        $this->addSql('ALTER TABLE cvs DROP FOREIGN KEY FK_5B9529D91BD8781');
        $this->addSql('ALTER TABLE job_candidate DROP FOREIGN KEY FK_F026155BE04EA9');
        $this->addSql('ALTER TABLE job_candidate DROP FOREIGN KEY FK_F02615591BD8781');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE candidate_company');
        $this->addSql('DROP TABLE cvs');
        $this->addSql('DROP TABLE job_candidate');
    }
}
