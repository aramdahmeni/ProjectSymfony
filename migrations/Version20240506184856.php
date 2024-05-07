<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506184856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6B192E FOREIGN KEY (id_classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F6B192E ON user (id_classe_id)');
        $this->addSql('ALTER TABLE enseignant_classe ADD CONSTRAINT FK_F670A5F4E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseignant_classe DROP FOREIGN KEY FK_F670A5F4E455FCC0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6B192E');
        $this->addSql('DROP INDEX IDX_8D93D649F6B192E ON user');
    }
}
