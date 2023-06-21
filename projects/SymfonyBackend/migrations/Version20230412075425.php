<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412075425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactor Entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round DROP CONSTRAINT fk_c5eeea344b4034dd');
        $this->addSql('DROP SEQUENCE game_player_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id INT NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, wins INT NOT NULL, games_played INT NOT NULL, is_verified BOOLEAN NOT NULL, profile_pic VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE game_player DROP CONSTRAINT fk_e52cd7ade48fd905');
        $this->addSql('ALTER TABLE game_player DROP CONSTRAINT fk_e52cd7ad99e6f5df');
        $this->addSql('DROP TABLE game_player');
        $this->addSql('ALTER TABLE player ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD lives INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD is_winner BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE player DROP name');
        $this->addSql('ALTER TABLE player DROP username');
        $this->addSql('ALTER TABLE player DROP email');
        $this->addSql('ALTER TABLE player DROP password');
        $this->addSql('ALTER TABLE player DROP profile_pic');
        $this->addSql('ALTER TABLE player DROP wins');
        $this->addSql('ALTER TABLE player DROP games_played');
        $this->addSql('ALTER TABLE player RENAME COLUMN is_verified TO alive');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65A76ED395 FOREIGN KEY (user_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65A76ED395 ON player (user_id)');
        $this->addSql('CREATE INDEX IDX_98197A65E48FD905 ON player (game_id)');
        $this->addSql('DROP INDEX idx_c5eeea344b4034dd');
        $this->addSql('ALTER TABLE round ADD number INT NOT NULL');
        $this->addSql('ALTER TABLE round RENAME COLUMN game_player_id TO player_id');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA3499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5EEEA3499E6F5DF ON round (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65A76ED395');
        $this->addSql('DROP SEQUENCE account_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE game_player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE game_player (id INT NOT NULL, game_id INT NOT NULL, player_id INT NOT NULL, lives INT DEFAULT NULL, alive BOOLEAN NOT NULL, is_winner BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_e52cd7ad99e6f5df ON game_player (player_id)');
        $this->addSql('CREATE INDEX idx_e52cd7ade48fd905 ON game_player (game_id)');
        $this->addSql('ALTER TABLE game_player ADD CONSTRAINT fk_e52cd7ade48fd905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_player ADD CONSTRAINT fk_e52cd7ad99e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE account');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65E48FD905');
        $this->addSql('DROP INDEX UNIQ_98197A65A76ED395');
        $this->addSql('DROP INDEX IDX_98197A65E48FD905');
        $this->addSql('ALTER TABLE player ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD profile_pic VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD wins INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD games_played INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD is_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE player DROP user_id');
        $this->addSql('ALTER TABLE player DROP game_id');
        $this->addSql('ALTER TABLE player DROP lives');
        $this->addSql('ALTER TABLE player DROP alive');
        $this->addSql('ALTER TABLE player DROP is_winner');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA3499E6F5DF');
        $this->addSql('DROP INDEX IDX_C5EEEA3499E6F5DF');
        $this->addSql('ALTER TABLE round ADD game_player_id INT NOT NULL');
        $this->addSql('ALTER TABLE round DROP player_id');
        $this->addSql('ALTER TABLE round DROP number');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT fk_c5eeea344b4034dd FOREIGN KEY (game_player_id) REFERENCES game_player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c5eeea344b4034dd ON round (game_player_id)');
    }
}
