<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209110406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP nom_user, DROP prenom_user, DROP telephone, DROP role, DROP adresse, DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD nom_user VARCHAR(255) NOT NULL, ADD prenom_user VARCHAR(255) NOT NULL, ADD telephone INT NOT NULL, ADD role VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD photo LONGBLOB NOT NULL');
    }
}
