<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209140058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dons_volontaire (dons_id INT NOT NULL, volontaire_id INT NOT NULL, INDEX IDX_D18940ADDDBFD07B (dons_id), INDEX IDX_D18940AD9DEA3983 (volontaire_id), PRIMARY KEY(dons_id, volontaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dons_volontaire ADD CONSTRAINT FK_D18940ADDDBFD07B FOREIGN KEY (dons_id) REFERENCES dons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dons_volontaire ADD CONSTRAINT FK_D18940AD9DEA3983 FOREIGN KEY (volontaire_id) REFERENCES volontaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dons ADD association_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT FK_E4F955FAEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_E4F955FAEFB9C8A5 ON dons (association_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dons_volontaire DROP FOREIGN KEY FK_D18940ADDDBFD07B');
        $this->addSql('ALTER TABLE dons_volontaire DROP FOREIGN KEY FK_D18940AD9DEA3983');
        $this->addSql('DROP TABLE dons_volontaire');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY FK_E4F955FAEFB9C8A5');
        $this->addSql('DROP INDEX IDX_E4F955FAEFB9C8A5 ON dons');
        $this->addSql('ALTER TABLE dons DROP association_id');
    }
}
