<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209134521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFC54C8C93 FOREIGN KEY (type_id) REFERENCES type_formation (id)');
        $this->addSql('CREATE INDEX IDX_404021BFC54C8C93 ON formation (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFC54C8C93');
        $this->addSql('DROP INDEX IDX_404021BFC54C8C93 ON formation');
        $this->addSql('ALTER TABLE formation DROP type_id');
    }
}
