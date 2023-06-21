<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526080528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Subround entity created and Player entity modified';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player ADD rounds_won INT DEFAULT NULL');
        $this->addSql('CREATE SEQUENCE subround_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subround (id INT NOT NULL, round_id INT NOT NULL, cards_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5D05EEA6005CA0 ON subround (round_id)');
        $this->addSql('ALTER TABLE subround ADD CONSTRAINT FK_BF5D05EEA6005CA0 FOREIGN KEY (round_id) REFERENCES round (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE play DROP CONSTRAINT fk_5e89debaa6005ca0');
        $this->addSql('DROP INDEX idx_5e89debaa6005ca0');
        $this->addSql('ALTER TABLE play RENAME COLUMN round_id TO subround_id');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBAAD90E129 FOREIGN KEY (subround_id) REFERENCES subround (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5E89DEBAAD90E129 ON play (subround_id)');
        $this->addSql('ALTER TABLE subround ADD winner INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP rounds_won');
        $this->addSql('ALTER TABLE play DROP CONSTRAINT FK_5E89DEBAAD90E129');
        $this->addSql('DROP SEQUENCE subround_id_seq CASCADE');
        $this->addSql('ALTER TABLE subround DROP CONSTRAINT FK_BF5D05EEA6005CA0');
        $this->addSql('DROP TABLE subround');
        $this->addSql('DROP INDEX IDX_5E89DEBAAD90E129');
        $this->addSql('ALTER TABLE play RENAME COLUMN subround_id TO round_id');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT fk_5e89debaa6005ca0 FOREIGN KEY (round_id) REFERENCES round (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5e89debaa6005ca0 ON play (round_id)');
        $this->addSql('ALTER TABLE subround DROP winner');
    }
}
