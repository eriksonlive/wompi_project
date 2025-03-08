<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308182218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payments (id INT NOT NULL, id_referencia INT DEFAULT NULL, valor INT DEFAULT NULL, pasarela VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, estado VARCHAR(255) DEFAULT NULL, vigencia_link TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, fecha_link TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, id_organizacion INT DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, customer_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payments.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payments');
    }
}
