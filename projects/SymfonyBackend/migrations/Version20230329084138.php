<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329084138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify properties of some entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD finished BOOLEAN NOT NULL');
        $this->addSql('DROP INDEX uniq_e52cd7ade48fd905');
        $this->addSql('CREATE INDEX IDX_E52CD7ADE48FD905 ON game_player (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_E52CD7ADE48FD905');
        $this->addSql('CREATE UNIQUE INDEX uniq_e52cd7ade48fd905 ON game_player (game_id)');
        $this->addSql('ALTER TABLE game DROP finished');
    }
}
