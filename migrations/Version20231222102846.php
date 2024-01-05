<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222102846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1CFE419E2');
        $this->addSql('DROP INDEX UNIQ_A45BDDC1CFE419E2 ON application');
        $this->addSql('ALTER TABLE application CHANGE cv_id curriculum_id INT NOT NULL');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC15AEA4428 FOREIGN KEY (curriculum_id) REFERENCES cvs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A45BDDC15AEA4428 ON application (curriculum_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC15AEA4428');
        $this->addSql('DROP INDEX UNIQ_A45BDDC15AEA4428 ON application');
        $this->addSql('ALTER TABLE application CHANGE curriculum_id cv_id INT NOT NULL');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1CFE419E2 FOREIGN KEY (cv_id) REFERENCES cvs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A45BDDC1CFE419E2 ON application (cv_id)');
    }
}
