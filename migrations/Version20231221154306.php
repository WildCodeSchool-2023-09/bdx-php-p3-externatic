<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221154306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_candidate (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_candidate_candidate (favorite_candidate_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_8643FD237EAB85EE (favorite_candidate_id), INDEX IDX_8643FD2391BD8781 (candidate_id), PRIMARY KEY(favorite_candidate_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_candidate_company (favorite_candidate_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_B048BC187EAB85EE (favorite_candidate_id), INDEX IDX_B048BC18979B1AD6 (company_id), PRIMARY KEY(favorite_candidate_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_candidate_candidate ADD CONSTRAINT FK_8643FD237EAB85EE FOREIGN KEY (favorite_candidate_id) REFERENCES favorite_candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_candidate_candidate ADD CONSTRAINT FK_8643FD2391BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_candidate_company ADD CONSTRAINT FK_B048BC187EAB85EE FOREIGN KEY (favorite_candidate_id) REFERENCES favorite_candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_candidate_company ADD CONSTRAINT FK_B048BC18979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_candidate_candidate DROP FOREIGN KEY FK_8643FD237EAB85EE');
        $this->addSql('ALTER TABLE favorite_candidate_candidate DROP FOREIGN KEY FK_8643FD2391BD8781');
        $this->addSql('ALTER TABLE favorite_candidate_company DROP FOREIGN KEY FK_B048BC187EAB85EE');
        $this->addSql('ALTER TABLE favorite_candidate_company DROP FOREIGN KEY FK_B048BC18979B1AD6');
        $this->addSql('DROP TABLE favorite_candidate');
        $this->addSql('DROP TABLE favorite_candidate_candidate');
        $this->addSql('DROP TABLE favorite_candidate_company');
    }
}
