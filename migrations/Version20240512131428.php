<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512131428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ch');
        $this->addSql('DROP TABLE evenements');
        $this->addSql('DROP TABLE opportunite');
        $this->addSql('DROP TABLE pr');
        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partenaire MODIFY id_Partenaire INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON partenaire');
        $this->addSql('ALTER TABLE partenaire DROP id_Partenaire, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE partenaire ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36A98DE13AC');
        $this->addSql('ALTER TABLE partenaire_societe ADD CONSTRAINT FK_694C36A98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP SharName, DROP ShareComment');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BCFBD2D FOREIGN KEY (post_reactions_id) REFERENCES post_reactions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D6BCFBD2D ON post (post_reactions_id)');
        $this->addSql('ALTER TABLE project ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEC59E234B FOREIGN KEY (idchat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE societe ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE societe ADD CONSTRAINT FK_19653DBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_19653DBDA76ED395 ON societe (user_id)');
        $this->addSql('ALTER TABLE sponsor MODIFY id_sponsor INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON sponsor');
        $this->addSql('ALTER TABLE sponsor CHANGE id_sponsor id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE sponsor ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F012F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sponsor_evenement ADD CONSTRAINT FK_2ED122F0FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_8D93D64919653DBD ON user');
        $this->addSql('ALTER TABLE user DROP societe');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ch (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, chatname VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, username VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evenements (id INT AUTO_INCREMENT NOT NULL, Titre VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Localisation VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nb_participant INT DEFAULT NULL, Date DATE NOT NULL, Image VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, heure TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE opportunite (id INT AUTO_INCREMENT NOT NULL, idtest_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, descreption VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_favorite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pr (id INT AUTO_INCREMENT NOT NULL, prname VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, stdate VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, enddate VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE test (id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, score INT NOT NULL, duree VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pdf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE partenaire MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON partenaire');
        $this->addSql('ALTER TABLE partenaire ADD id_Partenaire INT AUTO_INCREMENT NOT NULL, CHANGE id id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partenaire ADD PRIMARY KEY (id_Partenaire)');
        $this->addSql('ALTER TABLE partenaire_societe DROP FOREIGN KEY FK_694C36A98DE13AC');
        $this->addSql('ALTER TABLE partenaire_societe ADD CONSTRAINT FK_694C36A98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id_Partenaire) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BCFBD2D');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D6BCFBD2D ON post');
        $this->addSql('ALTER TABLE post ADD SharName VARCHAR(255) DEFAULT NULL, ADD ShareComment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEC59E234B');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP INDEX IDX_2FB3D0EEA76ED395 ON project');
        $this->addSql('ALTER TABLE project DROP user_id');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE societe DROP FOREIGN KEY FK_19653DBDA76ED395');
        $this->addSql('DROP INDEX UNIQ_19653DBDA76ED395 ON societe');
        $this->addSql('ALTER TABLE societe DROP user_id');
        $this->addSql('ALTER TABLE sponsor MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON sponsor');
        $this->addSql('ALTER TABLE sponsor CHANGE id id_sponsor INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE sponsor ADD PRIMARY KEY (id_sponsor)');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F012F7FB51');
        $this->addSql('ALTER TABLE sponsor_evenement DROP FOREIGN KEY FK_2ED122F0FD02F13');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491A9A7125');
        $this->addSql('ALTER TABLE user ADD societe INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64919653DBD ON user (societe)');
    }
}
