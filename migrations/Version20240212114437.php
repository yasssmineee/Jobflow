<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212114437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunite ADD idtest_id INT DEFAULT NULL, DROP nom, DROP descreption');
        $this->addSql('ALTER TABLE opportunite ADD CONSTRAINT FK_97889F98C1595637 FOREIGN KEY (idtest_id) REFERENCES test (id)');
        $this->addSql('CREATE INDEX IDX_97889F98C1595637 ON opportunite (idtest_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunite DROP FOREIGN KEY FK_97889F98C1595637');
        $this->addSql('DROP INDEX IDX_97889F98C1595637 ON opportunite');
        $this->addSql('ALTER TABLE opportunite ADD nom VARCHAR(255) NOT NULL, ADD descreption VARCHAR(255) NOT NULL, DROP idtest_id');
    }
}
