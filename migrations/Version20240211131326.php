<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211131326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sponsor_evenement (sponsor_id INT NOT NULL, evenement_id INT NOT NULL, INDEX IDX_2ED122F012F7FB51 (sponsor_id), INDEX IDX_2ED122F0FD02F13 (evenement_id), PRIMARY KEY(sponsor_id, evenement_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F012F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F0FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F012F7FB51');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F0FD02F13');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE sponsor_evenement');
    }
}
