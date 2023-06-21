<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329085520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify properties of some entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card ADD card_flipped BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game_player ADD alive BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE play DROP card_flipped');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_player DROP alive');
        $this->addSql('ALTER TABLE play ADD card_flipped BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP name');
        $this->addSql('ALTER TABLE card DROP card_flipped');
    }
}
