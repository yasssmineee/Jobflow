<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304084909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_reactions (id INT AUTO_INCREMENT NOT NULL, likes INT NOT NULL, dislike INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD post_reactions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BCFBD2D FOREIGN KEY (post_reactions_id) REFERENCES post_reactions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D6BCFBD2D ON post (post_reactions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BCFBD2D');
        $this->addSql('DROP TABLE post_reactions');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D6BCFBD2D ON post');
        $this->addSql('ALTER TABLE post DROP post_reactions_id');
    }
}
