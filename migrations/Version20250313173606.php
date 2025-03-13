<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313173606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE SEQUENCE customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        // $this->addSql('CREATE SEQUENCE payments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payments (id INT NOT NULL, customer INT NOT NULL, id_transaccion VARCHAR(255) DEFAULT NULL, id_referencia VARCHAR(255) DEFAULT NULL, valor INT NOT NULL, pasarela VARCHAR(255) DEFAULT \'wompi\' NOT NULL, link VARCHAR(255) DEFAULT NULL, estado VARCHAR(255) NOT NULL, vigencia_link TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, fecha_link TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, id_organizacion INT DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_65D29B3281398E09 ON payments (customer)');
        $this->addSql('COMMENT ON COLUMN payments.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3281398E09 FOREIGN KEY (customer) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_id_seq CASCADE');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B3281398E09');
        $this->addSql('DROP TABLE payments');
    }
}
