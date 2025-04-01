<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401102714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON produit_menu');
        $this->addSql('ALTER TABLE produit_menu ADD PRIMARY KEY (menus_id, produits_id)');
        $this->addSql('ALTER TABLE produits CHANGE price price NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON produit_menu');
        $this->addSql('ALTER TABLE produit_menu ADD PRIMARY KEY (produits_id, menus_id)');
    }
}
