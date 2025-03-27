<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327144034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tables_reservation DROP FOREIGN KEY FK_8052986785405FD2');
        $this->addSql('ALTER TABLE tables_reservation DROP FOREIGN KEY FK_80529867B83297E7');
        $this->addSql('DROP TABLE tables_reservation');
        $this->addSql('ALTER TABLE reservation ADD tables_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE date date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495585405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id)');
        $this->addSql('CREATE INDEX IDX_42C8495585405FD2 ON reservation (tables_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tables_reservation (tables_id INT NOT NULL, reservation_id INT NOT NULL, INDEX IDX_80529867B83297E7 (reservation_id), INDEX IDX_8052986785405FD2 (tables_id), PRIMARY KEY(tables_id, reservation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tables_reservation ADD CONSTRAINT FK_8052986785405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tables_reservation ADD CONSTRAINT FK_80529867B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495585405FD2');
        $this->addSql('DROP INDEX IDX_42C8495585405FD2 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP tables_id, CHANGE user_id user_id INT NOT NULL, CHANGE date date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
