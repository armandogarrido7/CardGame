<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328080134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify properties of Round entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round DROP CONSTRAINT fk_c5eeea345dfcd4b8');
        $this->addSql('DROP INDEX idx_c5eeea345dfcd4b8');
        $this->addSql('ALTER TABLE round ADD round_won BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE round ADD finished_round BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE round ADD lives_lost INT NOT NULL');
        $this->addSql('ALTER TABLE round DROP winner_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round ADD winner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE round DROP round_won');
        $this->addSql('ALTER TABLE round DROP finished_round');
        $this->addSql('ALTER TABLE round DROP lives_lost');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT fk_c5eeea345dfcd4b8 FOREIGN KEY (winner_id) REFERENCES game_player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c5eeea345dfcd4b8 ON round (winner_id)');
    }
}
