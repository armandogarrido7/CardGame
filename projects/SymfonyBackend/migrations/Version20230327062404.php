<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327062404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added Player';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profile_pic BYTEA NOT NULL, wins INT NOT NULL, games_played INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE player_card (player_id INT NOT NULL, card_id INT NOT NULL, PRIMARY KEY(player_id, card_id))');
        $this->addSql('CREATE INDEX IDX_B40EC8E099E6F5DF ON player_card (player_id)');
        $this->addSql('CREATE INDEX IDX_B40EC8E04ACC9A20 ON player_card (card_id)');
        $this->addSql('ALTER TABLE player_card ADD CONSTRAINT FK_B40EC8E099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_card ADD CONSTRAINT FK_B40EC8E04ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE player_id_seq CASCADE');
        $this->addSql('ALTER TABLE player_card DROP CONSTRAINT FK_B40EC8E099E6F5DF');
        $this->addSql('ALTER TABLE player_card DROP CONSTRAINT FK_B40EC8E04ACC9A20');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_card');
    }
}
