<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522080118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Entity remodeling';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD current_round_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD players_turns TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN game.players_turns IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT fk_7d3656a499e6f5df');
        $this->addSql('DROP INDEX uniq_7d3656a499e6f5df');
        $this->addSql('ALTER TABLE account DROP player_id');
        $this->addSql('DROP INDEX uniq_5e89debaa6005ca0');
        $this->addSql('ALTER TABLE play ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBA99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5E89DEBA99E6F5DF ON play (player_id)');
        $this->addSql('CREATE INDEX IDX_5E89DEBAA6005CA0 ON play (round_id)');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT fk_98197a654e825c80');
        $this->addSql('DROP INDEX idx_98197a654e825c80');
        $this->addSql('ALTER TABLE player DROP current_game_id');
        $this->addSql('ALTER TABLE prediction ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC899E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_36396FC899E6F5DF ON prediction (player_id)');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT fk_c5eeea3499e6f5df');
        $this->addSql('DROP INDEX idx_c5eeea3499e6f5df');
        $this->addSql('ALTER TABLE round DROP player_id');
        $this->addSql('ALTER TABLE round DROP round_won');
        $this->addSql('ALTER TABLE round DROP lives_lost');
        $this->addSql('ALTER TABLE round RENAME COLUMN finished_round TO finished');
        $this->addSql('ALTER TABLE round ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5EEEA34E48FD905 ON round (game_id)');
        $this->addSql('ALTER TABLE account ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA34E48FD905');
        $this->addSql('DROP INDEX IDX_C5EEEA34E48FD905');
        $this->addSql('ALTER TABLE round DROP game_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP current_round_number');
        $this->addSql('ALTER TABLE game DROP players_turns');
        $this->addSql('ALTER TABLE account ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT fk_7d3656a499e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_7d3656a499e6f5df ON account (player_id)');
        $this->addSql('ALTER TABLE player ADD current_game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT fk_98197a654e825c80 FOREIGN KEY (current_game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_98197a654e825c80 ON player (current_game_id)');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC899E6F5DF');
        $this->addSql('DROP INDEX IDX_36396FC899E6F5DF');
        $this->addSql('ALTER TABLE prediction DROP player_id');
        $this->addSql('ALTER TABLE round ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE round ADD round_won BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE round ADD lives_lost INT NOT NULL');
        $this->addSql('ALTER TABLE round RENAME COLUMN finished TO finished_round');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT fk_c5eeea3499e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c5eeea3499e6f5df ON round (player_id)');
        $this->addSql('ALTER TABLE play DROP CONSTRAINT FK_5E89DEBA99E6F5DF');
        $this->addSql('DROP INDEX IDX_5E89DEBA99E6F5DF');
        $this->addSql('DROP INDEX IDX_5E89DEBAA6005CA0');
        $this->addSql('ALTER TABLE play DROP player_id');
        $this->addSql('CREATE UNIQUE INDEX uniq_5e89debaa6005ca0 ON play (round_id)');

    }
}
