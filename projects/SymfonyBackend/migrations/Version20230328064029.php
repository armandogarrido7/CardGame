<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328064029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify properties of some entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE play DROP cards_number');
        $this->addSql('ALTER TABLE round ADD winner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA345DFCD4B8 FOREIGN KEY (winner_id) REFERENCES game_player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5EEEA345DFCD4B8 ON round (winner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE play ADD cards_number INT NOT NULL');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA345DFCD4B8');
        $this->addSql('DROP INDEX IDX_C5EEEA345DFCD4B8');
        $this->addSql('ALTER TABLE round DROP winner_id');
    }
}
