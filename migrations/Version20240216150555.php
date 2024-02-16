<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216150555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, nb_participant INT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, descreption VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire_societe (partenaire_id INT NOT NULL, societe_id INT NOT NULL, INDEX IDX_694C36A98DE13AC (partenaire_id), INDEX IDX_694C36AFCF77503 (societe_id), PRIMARY KEY(partenaire_id, societe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, descreption VARCHAR(255) NOT NULL, siteweb VARCHAR(255) NOT NULL, numtel INT NOT NULL, secteur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor_evenement (sponsor_id INT NOT NULL, evenement_id INT NOT NULL, INDEX IDX_2ED122F012F7FB51 (sponsor_id), INDEX IDX_2ED122F0FD02F13 (evenement_id), PRIMARY KEY(sponsor_id, evenement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partenaire_societe ADD CONSTRAINT FK_694C36A98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partenaire_societe ADD CONSTRAINT FK_694C36AFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F012F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F0FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36A98DE13AC');
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36AFCF77503');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F012F7FB51');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F0FD02F13');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE partenaire_societe');
        $this->addSql('DROP TABLE societe');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE sponsor_evenement');
    }
}
