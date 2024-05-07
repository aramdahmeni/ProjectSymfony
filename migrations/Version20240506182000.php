<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506182000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseignant_classe DROP FOREIGN KEY FK_F670A5F4E455FCC0');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3F6B192E');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('ALTER TABLE user ADD id_classe_id INT NOT NULL, ADD user_type VARCHAR(255) NOT NULL, ADD type_et INT DEFAULT NULL, ADD specialite VARCHAR(255) DEFAULT NULL, ADD type_ens INT DEFAULT NULL, ADD code_ens VARCHAR(255) DEFAULT NULL, ADD nb_annee_exp INT DEFAULT NULL, ADD matiere VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6B192E FOREIGN KEY (id_classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F6B192E ON user (id_classe_id)');
        $this->addSql('ALTER TABLE enseignant_classe DROP FOREIGN KEY FK_F670A5F4E455FCC0');
        $this->addSql('ALTER TABLE enseignant_classe ADD CONSTRAINT FK_F670A5F4E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, type_ens INT NOT NULL, code_ens VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nb_annee_exp INT NOT NULL, matiere VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, id_classe_id INT NOT NULL, type_et INT NOT NULL, specialite VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_717E22E3F6B192E (id_classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3F6B192E FOREIGN KEY (id_classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE enseignant_classe DROP FOREIGN KEY FK_F670A5F4E455FCC0');
        $this->addSql('ALTER TABLE enseignant_classe ADD CONSTRAINT FK_F670A5F4E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6B192E');
        $this->addSql('DROP INDEX IDX_8D93D649F6B192E ON user');
        $this->addSql('ALTER TABLE user DROP id_classe_id, DROP user_type, DROP type_et, DROP specialite, DROP type_ens, DROP code_ens, DROP nb_annee_exp, DROP matiere');
    }
}
