<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327125033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify Prediction';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT fk_36396fc899e6f5df');
        $this->addSql('DROP INDEX idx_36396fc899e6f5df');
        $this->addSql('ALTER TABLE prediction DROP player_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prediction ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT fk_36396fc899e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_36396fc899e6f5df ON prediction (player_id)');
    }
}
