<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512125817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, descreption VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, descreption VARCHAR(255) NOT NULL, siteweb VARCHAR(255) NOT NULL, numtel INT NOT NULL, secteur VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_19653DBDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE societe ADD CONSTRAINT FK_19653DBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE ch');
        $this->addSql('DROP TABLE evenements');
        $this->addSql('DROP TABLE opportunite');
        $this->addSql('DROP TABLE pr');
        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36A98DE13AC');
        $this->addSql('ALTER TABLE partenaire_societe ADD CONSTRAINT FK_694C36A98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP INDEX IDX_5A8A6C8D6BCFBD2D, ADD UNIQUE INDEX UNIQ_5A8A6C8D6BCFBD2D (post_reactions_id)');
        $this->addSql('ALTER TABLE post DROP SharName, DROP ShareComment');
        $this->addSql('ALTER TABLE project ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
        $this->addSql('ALTER TABLE sponsor MODIFY id_sponsor INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON sponsor');
        $this->addSql('ALTER TABLE sponsor CHANGE id_sponsor id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE sponsor ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F012F7FB51');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F012F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919653DBD');
        $this->addSql('DROP INDEX UNIQ_8D93D64919653DBD ON user');
        $this->addSql('ALTER TABLE user DROP societe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36A98DE13AC');
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36AFCF77503');
        $this->addSql('CREATE TABLE ch (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, chatname VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, username VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evenements (id INT AUTO_INCREMENT NOT NULL, Titre VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Localisation VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nb_participant INT DEFAULT NULL, Date DATE NOT NULL, Image VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, heure TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE opportunite (id INT AUTO_INCREMENT NOT NULL, idtest_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, descreption VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_favorite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pr (id INT AUTO_INCREMENT NOT NULL, prname VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, stdate VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, enddate VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE test (id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, score INT NOT NULL, duree VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pdf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE societe DROP FOREIGN KEY FK_19653DBDA76ED395');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE societe');
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36A98DE13AC');
        $this->addSql('ALTER TABLE partenaire_societe ADD CONSTRAINT FK_694C36A98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id_Partenaire) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP INDEX UNIQ_5A8A6C8D6BCFBD2D, ADD INDEX IDX_5A8A6C8D6BCFBD2D (post_reactions_id)');
        $this->addSql('ALTER TABLE post ADD SharName VARCHAR(255) DEFAULT NULL, ADD ShareComment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP INDEX IDX_2FB3D0EEA76ED395 ON project');
        $this->addSql('ALTER TABLE project DROP user_id');
        $this->addSql('ALTER TABLE sponsor MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON sponsor');
        $this->addSql('ALTER TABLE sponsor CHANGE id id_sponsor INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE sponsor ADD PRIMARY KEY (id_sponsor)');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F012F7FB51');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F012F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id_sponsor) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD societe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919653DBD FOREIGN KEY (societe) REFERENCES societe (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64919653DBD ON user (societe)');
    }
}
