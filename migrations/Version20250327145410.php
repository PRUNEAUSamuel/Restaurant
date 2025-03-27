<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327145410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495585405FD2');
        $this->addSql('DROP INDEX IDX_42C8495585405FD2 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE tables_id table_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ECFF285C FOREIGN KEY (table_id) REFERENCES tables (id)');
        $this->addSql('CREATE INDEX IDX_42C84955ECFF285C ON reservation (table_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ECFF285C');
        $this->addSql('DROP INDEX IDX_42C84955ECFF285C ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE table_id tables_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495585405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id)');
        $this->addSql('CREATE INDEX IDX_42C8495585405FD2 ON reservation (tables_id)');
    }
}
