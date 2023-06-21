<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\GameStatus;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508102624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created GameStatus table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE game_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE game_status (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE game ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C6BF700BD FOREIGN KEY (status_id) REFERENCES game_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_232B318C6BF700BD ON game (status_id)');
        $this->addSql("INSERT INTO game_status(id, name, description) VALUES (1, '".GameStatus::WAITING_FOR_PLAYERS."', 'Waiting for Players...')");
        $this->addSql("INSERT INTO game_status(id, name, description) VALUES (2, '".GameStatus::WAITING_HOST_TO_START."', 'Waiting for the host to start the game...')");
        $this->addSql("INSERT INTO game_status(id, name, description) VALUES (3, '".GameStatus::STARTED."', 'Game in Progress')");
        $this->addSql("INSERT INTO game_status(id, name, description) VALUES (4, '".GameStatus::FINISHED."', 'Game Finished')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C6BF700BD');
        $this->addSql('DROP SEQUENCE game_status_id_seq CASCADE');
        $this->addSql('DROP TABLE game_status');
        $this->addSql('DROP INDEX IDX_232B318C6BF700BD');
        $this->addSql('ALTER TABLE game DROP status_id');
        $this->addSql('DELETE FROM game_status *');
    }
}
