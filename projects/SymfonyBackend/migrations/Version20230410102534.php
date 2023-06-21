<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410102534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tables synchronised with Sequences';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER SEQUENCE player_id_seq OWNED BY player.id');
        $this->addSql('ALTER SEQUENCE game_id_seq OWNED BY game.id');
        $this->addSql('ALTER SEQUENCE card_id_seq OWNED BY card.id');
        $this->addSql('ALTER SEQUENCE game_player_id_seq OWNED BY game_player.id');
        $this->addSql('ALTER SEQUENCE prediction_id_seq OWNED BY prediction.id');
        $this->addSql('ALTER SEQUENCE round_id_seq OWNED BY round.id');
        $this->addSql('ALTER SEQUENCE play_id_seq OWNED BY play.id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
