<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328063155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify properties of some entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_player ADD lives INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prediction ADD will_win BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE prediction ALTER rounds_won DROP NOT NULL');
        $this->addSql('ALTER TABLE round ADD cards_number INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prediction DROP will_win');
        $this->addSql('ALTER TABLE prediction ALTER rounds_won SET NOT NULL');
        $this->addSql('ALTER TABLE game_player DROP lives');
        $this->addSql('ALTER TABLE round DROP cards_number');
    }
}
