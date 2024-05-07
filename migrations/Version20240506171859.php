<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506171859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, type_ad INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, id_classe INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, type_ens INT NOT NULL, code_ens VARCHAR(255) NOT NULL, nb_annee_exp INT NOT NULL, matiere VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant_classe (enseignant_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_F670A5F4E455FCC0 (enseignant_id), INDEX IDX_F670A5F48F5EA509 (classe_id), PRIMARY KEY(enseignant_id, classe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, id_classe_id INT NOT NULL, type_et INT NOT NULL, specialite VARCHAR(255) NOT NULL, INDEX IDX_717E22E3F6B192E (id_classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enseignant_classe ADD CONSTRAINT FK_F670A5F4E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enseignant_classe ADD CONSTRAINT FK_F670A5F48F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3F6B192E FOREIGN KEY (id_classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD numtel INT NOT NULL, ADD type INT NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP username');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseignant_classe DROP FOREIGN KEY FK_F670A5F4E455FCC0');
        $this->addSql('ALTER TABLE enseignant_classe DROP FOREIGN KEY FK_F670A5F48F5EA509');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3F6B192E');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE enseignant_classe');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(20) NOT NULL, DROP nom, DROP prenom, DROP email, DROP numtel, DROP type, DROP password');
    }
}
