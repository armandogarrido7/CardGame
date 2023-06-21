<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230512082315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modified some Player properties';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_161498D399E6F5DF ON card (player_id)');
        $this->addSql('ALTER TABLE player ADD current_game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A654E825C80 FOREIGN KEY (current_game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_98197A654E825C80 ON player (current_game_id)');
        $this->addSql('ALTER TABLE game ADD turn_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TABLE player_card (player_id INT NOT NULL, card_id INT NOT NULL, PRIMARY KEY(player_id, card_id))');
        $this->addSql('CREATE INDEX IDX_B40EC8E099E6F5DF ON player_card (player_id)');
        $this->addSql('CREATE INDEX IDX_B40EC8E04ACC9A20 ON player_card (card_id)');
        $this->addSql('ALTER TABLE player_card ADD CONSTRAINT FK_B40EC8E099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_card ADD CONSTRAINT FK_B40EC8E04ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT fk_161498d399e6f5df');
        $this->addSql('DROP INDEX idx_161498d399e6f5df');
        $this->addSql('ALTER TABLE card DROP player_id');
        $this->addSql('ALTER TABLE game ADD current_cards_number INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A654E825C80');
        $this->addSql('DROP INDEX IDX_98197A654E825C80');
        $this->addSql('ALTER TABLE player DROP current_game_id');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D399E6F5DF');
        $this->addSql('DROP INDEX IDX_161498D399E6F5DF');
        $this->addSql('ALTER TABLE card DROP player_id');
        $this->addSql('ALTER TABLE game DROP turn_type');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player_card DROP CONSTRAINT FK_B40EC8E099E6F5DF');
        $this->addSql('ALTER TABLE player_card DROP CONSTRAINT FK_B40EC8E04ACC9A20');
        $this->addSql('DROP TABLE player_card');
        $this->addSql('ALTER TABLE card ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT fk_161498d399e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_161498d399e6f5df ON card (player_id)');
        $this->addSql('ALTER TABLE game DROP current_cards_number');
    }
}
