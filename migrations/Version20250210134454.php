<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210134454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menus_produits (menus_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_DCCC1E8614041B84 (menus_id), INDEX IDX_DCCC1E86CD11A2CF (produits_id), PRIMARY KEY(menus_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menus_produits ADD CONSTRAINT FK_DCCC1E8614041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_produits ADD CONSTRAINT FK_DCCC1E86CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus ADD relation VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menus_produits DROP FOREIGN KEY FK_DCCC1E8614041B84');
        $this->addSql('ALTER TABLE menus_produits DROP FOREIGN KEY FK_DCCC1E86CD11A2CF');
        $this->addSql('DROP TABLE menus_produits');
        $this->addSql('ALTER TABLE menus DROP relation');
    }
}
