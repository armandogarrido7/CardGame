<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518112315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Game and Account changes';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD allowed_predictions TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN game.allowed_predictions IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE game ADD current_player INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A499E6F5DF ON account (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP allowed_predictions');
        $this->addSql('ALTER TABLE game DROP current_player');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A499E6F5DF');
        $this->addSql('DROP INDEX UNIQ_7D3656A499E6F5DF');
        $this->addSql('ALTER TABLE account DROP player_id');
    }
}
