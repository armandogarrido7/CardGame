<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327125508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify Play';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE play DROP CONSTRAINT fk_5e89deba99e6f5df');
        $this->addSql('DROP INDEX idx_5e89deba4acc9a20');
        $this->addSql('DROP INDEX idx_5e89deba99e6f5df');
        $this->addSql('ALTER TABLE play ADD round_id INT NOT NULL');
        $this->addSql('ALTER TABLE play DROP player_id');
        $this->addSql('ALTER TABLE play DROP round');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBAA6005CA0 FOREIGN KEY (round_id) REFERENCES round (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E89DEBA4ACC9A20 ON play (card_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E89DEBAA6005CA0 ON play (round_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE play DROP CONSTRAINT FK_5E89DEBAA6005CA0');
        $this->addSql('DROP INDEX UNIQ_5E89DEBA4ACC9A20');
        $this->addSql('DROP INDEX UNIQ_5E89DEBAA6005CA0');
        $this->addSql('ALTER TABLE play ADD round INT NOT NULL');
        $this->addSql('ALTER TABLE play RENAME COLUMN round_id TO player_id');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT fk_5e89deba99e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5e89deba4acc9a20 ON play (card_id)');
        $this->addSql('CREATE INDEX idx_5e89deba99e6f5df ON play (player_id)');
    }
}
