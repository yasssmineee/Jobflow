<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302180127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postuler (id INT AUTO_INCREMENT NOT NULL, motivation_text VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, id_opportunite_id INT DEFAULT NULL, INDEX IDX_8EC5A68DBD9D0C2E (id_opportunite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE postuler ADD CONSTRAINT FK_8EC5A68DBD9D0C2E FOREIGN KEY (id_opportunite_id) REFERENCES opportunite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postuler DROP FOREIGN KEY FK_8EC5A68DBD9D0C2E');
        $this->addSql('DROP TABLE postuler');
    }
}
