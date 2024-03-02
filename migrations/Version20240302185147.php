<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302185147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opportunite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, descreption VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_favorite TINYINT(1) NOT NULL, idtest_id INT DEFAULT NULL, INDEX IDX_97889F98C1595637 (idtest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE postuler (id INT AUTO_INCREMENT NOT NULL, motivation_text VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, id_opportunite_id INT DEFAULT NULL, INDEX IDX_8EC5A68DBD9D0C2E (id_opportunite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, score INT NOT NULL, duree VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE opportunite ADD CONSTRAINT FK_97889F98C1595637 FOREIGN KEY (idtest_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE postuler ADD CONSTRAINT FK_8EC5A68DBD9D0C2E FOREIGN KEY (id_opportunite_id) REFERENCES opportunite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunite DROP FOREIGN KEY FK_97889F98C1595637');
        $this->addSql('ALTER TABLE postuler DROP FOREIGN KEY FK_8EC5A68DBD9D0C2E');
        $this->addSql('DROP TABLE opportunite');
        $this->addSql('DROP TABLE postuler');
        $this->addSql('DROP TABLE test');
    }
}
