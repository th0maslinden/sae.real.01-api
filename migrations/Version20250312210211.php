<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312210211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(40) NOT NULL, type VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, professionnel_id INT DEFAULT NULL, date DATE DEFAULT NULL, heure_debut TIME DEFAULT NULL, heure_fin TIME DEFAULT NULL, note VARCHAR(1000) DEFAULT NULL, raison VARCHAR(50) DEFAULT NULL, INDEX IDX_DF7DFD0E6B899279 (patient_id), INDEX IDX_DF7DFD0E8A49CC82 (professionnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, avatar LONGBLOB DEFAULT NULL, email VARCHAR(100) NOT NULL, dtype VARCHAR(255) NOT NULL, nom VARCHAR(40) DEFAULT NULL, prenom VARCHAR(40) DEFAULT NULL, pathologie VARCHAR(100) DEFAULT NULL, specialite VARCHAR(40) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E8A49CC82 FOREIGN KEY (professionnel_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E6B899279');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E8A49CC82');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE user');
    }
}
