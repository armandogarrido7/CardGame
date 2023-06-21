<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531063027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Game entity modified';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD winner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_232B318C5DFCD4B8 ON game (winner_id)');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318c5dfcd4b8');
        $this->addSql('DROP INDEX idx_232b318c5dfcd4b8');
        $this->addSql('ALTER TABLE game RENAME COLUMN winner_id TO winner');
        $this->addSql('DROP INDEX uniq_98197a659b6b5fba');
        $this->addSql('CREATE INDEX IDX_98197A659B6B5FBA ON player (account_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C5DFCD4B8');
        $this->addSql('DROP INDEX IDX_232B318C5DFCD4B8');
        $this->addSql('ALTER TABLE game DROP winner_id');
        $this->addSql('ALTER TABLE game RENAME COLUMN winner TO winner_id');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318c5dfcd4b8 FOREIGN KEY (winner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_232b318c5dfcd4b8 ON game (winner_id)');
        $this->addSql('DROP INDEX IDX_98197A659B6B5FBA');
        $this->addSql('CREATE UNIQUE INDEX uniq_98197a659b6b5fba ON player (account_id)');
    }
}
