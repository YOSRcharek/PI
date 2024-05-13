<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513182311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, bio VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, domaine_activite VARCHAR(255) NOT NULL, telephone INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, document LONGBLOB NOT NULL, date_demande DATE NOT NULL, password VARCHAR(255) NOT NULL, active_compte TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FD8521CCE7927C74 (email), UNIQUE INDEX UNIQ_FD8521CC9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, idpost_id INT NOT NULL, username_id INT NOT NULL, contentcomment VARCHAR(255) NOT NULL, upvotes INT NOT NULL, createdatcomment DATETIME NOT NULL, INDEX IDX_9474526C948D5142 (idpost_id), INDEX IDX_9474526CED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_like (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_8A55E25FF8697D13 (comment_id), INDEX IDX_8A55E25FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dons (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, association_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_mis_don DATE NOT NULL, INDEX IDX_E4F955FAC54C8C93 (type_id), INDEX IDX_E4F955FAEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dons_volontaire (dons_id INT NOT NULL, volontaire_id INT NOT NULL, INDEX IDX_D18940ADDDBFD07B (dons_id), INDEX IDX_D18940AD9DEA3983 (volontaire_id), PRIMARY KEY(dons_id, volontaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, association_id INT DEFAULT NULL, nom_event VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, localisation VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, capacite_max INT NOT NULL, capacite_actuelle INT NOT NULL, INDEX IDX_3BAE0AA7C54C8C93 (type_id), INDEX IDX_3BAE0AA7EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, date_fin DATE NOT NULL, date_debut DATE NOT NULL, INDEX IDX_404021BFEFB9C8A5 (association_id), INDEX IDX_404021BFC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, nom_membre VARCHAR(255) NOT NULL, prenom_membre VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email_membre VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, INDEX IDX_F6B4FB29EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, username_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, rating INT NOT NULL, createdat DATETIME NOT NULL, visible TINYINT(1) NOT NULL, image VARCHAR(255) DEFAULT NULL, updatedat DATETIME DEFAULT NULL, video VARCHAR(255) DEFAULT NULL, quote VARCHAR(255) DEFAULT NULL, INDEX IDX_5A8A6C8DED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, nom_projet VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_50159CA9EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, reported_post_id INT NOT NULL, username_id INT NOT NULL, reason VARCHAR(255) NOT NULL, details VARCHAR(255) DEFAULT NULL, hide_post TINYINT(1) DEFAULT NULL, INDEX IDX_C42F7784EC0086D7 (reported_post_id), INDEX IDX_C42F7784ED766068 (username_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, commentaire_id INT DEFAULT NULL, association_id INT DEFAULT NULL, nom_service VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, disponibilite TINYINT(1) NOT NULL, INDEX IDX_E19D9AD2BCF5E72D (categorie_id), UNIQUE INDEX UNIQ_E19D9AD2BA9CD190 (commentaire_id), INDEX IDX_E19D9AD2EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_dons (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_event (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_formation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, bio VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volontaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone INT NOT NULL, photo LONGBLOB NOT NULL, adresse VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volontaire_event (volontaire_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_8F8372589DEA3983 (volontaire_id), INDEX IDX_8F83725871F7E88B (event_id), PRIMARY KEY(volontaire_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volontaire_service (volontaire_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_2C672A839DEA3983 (volontaire_id), INDEX IDX_2C672A83ED5CA9E6 (service_id), PRIMARY KEY(volontaire_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CC9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C948D5142 FOREIGN KEY (idpost_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT FK_E4F955FAC54C8C93 FOREIGN KEY (type_id) REFERENCES type_dons (id)');
        $this->addSql('ALTER TABLE dons ADD CONSTRAINT FK_E4F955FAEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE dons_volontaire ADD CONSTRAINT FK_D18940ADDDBFD07B FOREIGN KEY (dons_id) REFERENCES dons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dons_volontaire ADD CONSTRAINT FK_D18940AD9DEA3983 FOREIGN KEY (volontaire_id) REFERENCES volontaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES type_event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFC54C8C93 FOREIGN KEY (type_id) REFERENCES type_formation (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784EC0086D7 FOREIGN KEY (reported_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784ED766068 FOREIGN KEY (username_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE volontaire_event ADD CONSTRAINT FK_8F8372589DEA3983 FOREIGN KEY (volontaire_id) REFERENCES volontaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volontaire_event ADD CONSTRAINT FK_8F83725871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volontaire_service ADD CONSTRAINT FK_2C672A839DEA3983 FOREIGN KEY (volontaire_id) REFERENCES volontaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volontaire_service ADD CONSTRAINT FK_2C672A83ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CC9D86650F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C948D5142');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CED766068');
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FF8697D13');
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FA76ED395');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY FK_E4F955FAC54C8C93');
        $this->addSql('ALTER TABLE dons DROP FOREIGN KEY FK_E4F955FAEFB9C8A5');
        $this->addSql('ALTER TABLE dons_volontaire DROP FOREIGN KEY FK_D18940ADDDBFD07B');
        $this->addSql('ALTER TABLE dons_volontaire DROP FOREIGN KEY FK_D18940AD9DEA3983');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EFB9C8A5');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFEFB9C8A5');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFC54C8C93');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB29EFB9C8A5');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DED766068');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9EFB9C8A5');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784EC0086D7');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784ED766068');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BCF5E72D');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BA9CD190');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2EFB9C8A5');
        $this->addSql('ALTER TABLE volontaire_event DROP FOREIGN KEY FK_8F8372589DEA3983');
        $this->addSql('ALTER TABLE volontaire_event DROP FOREIGN KEY FK_8F83725871F7E88B');
        $this->addSql('ALTER TABLE volontaire_service DROP FOREIGN KEY FK_2C672A839DEA3983');
        $this->addSql('ALTER TABLE volontaire_service DROP FOREIGN KEY FK_2C672A83ED5CA9E6');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_like');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE dons');
        $this->addSql('DROP TABLE dons_volontaire');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE type_dons');
        $this->addSql('DROP TABLE type_event');
        $this->addSql('DROP TABLE type_formation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE volontaire');
        $this->addSql('DROP TABLE volontaire_event');
        $this->addSql('DROP TABLE volontaire_service');
    }
}
