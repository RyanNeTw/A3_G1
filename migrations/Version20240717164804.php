<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717164804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE drink ADD picture_id INT NOT NULL');
        $this->addSql('ALTER TABLE drink ADD CONSTRAINT FK_DBE40D1EE45BDBF FOREIGN KEY (picture_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBE40D1EE45BDBF ON drink (picture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE drink DROP CONSTRAINT FK_DBE40D1EE45BDBF');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP INDEX UNIQ_DBE40D1EE45BDBF');
        $this->addSql('ALTER TABLE drink DROP picture_id');
    }
}
