<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209132119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE volontaire_event (volontaire_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_8F8372589DEA3983 (volontaire_id), INDEX IDX_8F83725871F7E88B (event_id), PRIMARY KEY(volontaire_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volontaire_event ADD CONSTRAINT FK_8F8372589DEA3983 FOREIGN KEY (volontaire_id) REFERENCES volontaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volontaire_event ADD CONSTRAINT FK_8F83725871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volontaire_event DROP FOREIGN KEY FK_8F8372589DEA3983');
        $this->addSql('ALTER TABLE volontaire_event DROP FOREIGN KEY FK_8F83725871F7E88B');
        $this->addSql('DROP TABLE volontaire_event');
    }
}
