<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310115125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_menu (produits_id INT NOT NULL, menus_id INT NOT NULL, INDEX IDX_549D477CCD11A2CF (produits_id), INDEX IDX_549D477C14041B84 (menus_id), PRIMARY KEY(produits_id, menus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_menu ADD CONSTRAINT FK_549D477CCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_menu ADD CONSTRAINT FK_549D477C14041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_produits DROP FOREIGN KEY FK_DCCC1E8614041B84');
        $this->addSql('ALTER TABLE menus_produits DROP FOREIGN KEY FK_DCCC1E86CD11A2CF');
        $this->addSql('DROP TABLE menus_produits');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        $this->addSql('DROP INDEX IDX_5F9E962A9D86650F ON comments');
        $this->addSql('ALTER TABLE comments CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A9D86650F ON comments (user_id_id)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menus_produits (menus_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_DCCC1E8614041B84 (menus_id), INDEX IDX_DCCC1E86CD11A2CF (produits_id), PRIMARY KEY(menus_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE menus_produits ADD CONSTRAINT FK_DCCC1E8614041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_produits ADD CONSTRAINT FK_DCCC1E86CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_menu DROP FOREIGN KEY FK_549D477CCD11A2CF');
        $this->addSql('ALTER TABLE produit_menu DROP FOREIGN KEY FK_549D477C14041B84');
        $this->addSql('DROP TABLE produit_menu');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        $this->addSql('DROP INDEX IDX_5F9E962A9D86650F ON comments');
        $this->addSql('ALTER TABLE comments CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A9D86650F ON comments (user_id)');
        $this->addSql('ALTER TABLE user DROP is_verified, CHANGE roles roles VARCHAR(255) NOT NULL COLLATE `utf8mb4_bin`');
    }
}
