<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327124249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Multiple Entities modified';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE game_player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE play_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prediction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE round_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game_player (id INT NOT NULL, game_id INT NOT NULL, player_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52CD7ADE48FD905 ON game_player (game_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52CD7AD99E6F5DF ON game_player (player_id)');
        $this->addSql('CREATE TABLE play (id INT NOT NULL, player_id INT NOT NULL, card_id INT NOT NULL, round INT NOT NULL, cards_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E89DEBA99E6F5DF ON play (player_id)');
        $this->addSql('CREATE INDEX IDX_5E89DEBA4ACC9A20 ON play (card_id)');
        $this->addSql('CREATE TABLE prediction (id INT NOT NULL, player_id INT NOT NULL, round_id INT NOT NULL, rounds_won INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_36396FC899E6F5DF ON prediction (player_id)');
        $this->addSql('CREATE INDEX IDX_36396FC8A6005CA0 ON prediction (round_id)');
        $this->addSql('CREATE TABLE round (id INT NOT NULL, game_player_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5EEEA344B4034DD ON round (game_player_id)');
        $this->addSql('ALTER TABLE game_player ADD CONSTRAINT FK_E52CD7ADE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_player ADD CONSTRAINT FK_E52CD7AD99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBA99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBA4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC899E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8A6005CA0 FOREIGN KEY (round_id) REFERENCES round (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA344B4034DD FOREIGN KEY (game_player_id) REFERENCES game_player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_card DROP CONSTRAINT fk_b40ec8e099e6f5df');
        $this->addSql('ALTER TABLE player_card DROP CONSTRAINT fk_b40ec8e04acc9a20');
        $this->addSql('DROP TABLE player_card');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE game_player_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE play_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prediction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE round_id_seq CASCADE');
        $this->addSql('CREATE TABLE player_card (player_id INT NOT NULL, card_id INT NOT NULL, PRIMARY KEY(player_id, card_id))');
        $this->addSql('CREATE INDEX idx_b40ec8e04acc9a20 ON player_card (card_id)');
        $this->addSql('CREATE INDEX idx_b40ec8e099e6f5df ON player_card (player_id)');
        $this->addSql('ALTER TABLE player_card ADD CONSTRAINT fk_b40ec8e099e6f5df FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_card ADD CONSTRAINT fk_b40ec8e04acc9a20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_player DROP CONSTRAINT FK_E52CD7ADE48FD905');
        $this->addSql('ALTER TABLE game_player DROP CONSTRAINT FK_E52CD7AD99E6F5DF');
        $this->addSql('ALTER TABLE play DROP CONSTRAINT FK_5E89DEBA99E6F5DF');
        $this->addSql('ALTER TABLE play DROP CONSTRAINT FK_5E89DEBA4ACC9A20');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC899E6F5DF');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC8A6005CA0');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA344B4034DD');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_player');
        $this->addSql('DROP TABLE play');
        $this->addSql('DROP TABLE prediction');
        $this->addSql('DROP TABLE round');
    }
}
