<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717155045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE drink_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE drink (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, waiter_id_id INT DEFAULT NULL, barman_id_id INT DEFAULT NULL, table_number INT DEFAULT NULL, order_status VARCHAR(255) DEFAULT \'Pending\' NOT NULL, created_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F52993989DA2E63A ON "order" (waiter_id_id)');
        $this->addSql('CREATE INDEX IDX_F5299398F2000AA3 ON "order" (barman_id_id)');
        $this->addSql('CREATE TABLE order_drink (order_id INT NOT NULL, drink_id INT NOT NULL, PRIMARY KEY(order_id, drink_id))');
        $this->addSql('CREATE INDEX IDX_8E20342C8D9F6D38 ON order_drink (order_id)');
        $this->addSql('CREATE INDEX IDX_8E20342C36AA4BB4 ON order_drink (drink_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989DA2E63A FOREIGN KEY (waiter_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398F2000AA3 FOREIGN KEY (barman_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C8D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE drink_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989DA2E63A');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398F2000AA3');
        $this->addSql('ALTER TABLE order_drink DROP CONSTRAINT FK_8E20342C8D9F6D38');
        $this->addSql('ALTER TABLE order_drink DROP CONSTRAINT FK_8E20342C36AA4BB4');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_drink');
        $this->addSql('DROP TABLE "user"');
    }
}
