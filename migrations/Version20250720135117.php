<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720135117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE montre ADD marque_id INT NOT NULL, DROP marque');
        $this->addSql('ALTER TABLE montre ADD CONSTRAINT FK_B61A93A44827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('CREATE INDEX IDX_B61A93A44827B9B2 ON montre (marque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE montre DROP FOREIGN KEY FK_B61A93A44827B9B2');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP INDEX IDX_B61A93A44827B9B2 ON montre');
        $this->addSql('ALTER TABLE montre ADD marque VARCHAR(255) DEFAULT NULL, DROP marque_id');
    }
}
