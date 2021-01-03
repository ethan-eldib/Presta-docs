<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210101132746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288EE43BA2D');
        $this->addSql('DROP INDEX IDX_A2B07288EE43BA2D ON documents');
        $this->addSql('ALTER TABLE documents CHANGE forlders_id folders_id INT NOT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072887FB5B120 FOREIGN KEY (folders_id) REFERENCES folders (id)');
        $this->addSql('CREATE INDEX IDX_A2B072887FB5B120 ON documents (folders_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072887FB5B120');
        $this->addSql('DROP INDEX IDX_A2B072887FB5B120 ON documents');
        $this->addSql('ALTER TABLE documents CHANGE folders_id forlders_id INT NOT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288EE43BA2D FOREIGN KEY (forlders_id) REFERENCES folders (id)');
        $this->addSql('CREATE INDEX IDX_A2B07288EE43BA2D ON documents (forlders_id)');
    }
}
