<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427084132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Deleted Account properties';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP username');
        $this->addSql('ALTER TABLE account DROP password');
        $this->addSql('ALTER TABLE card DROP card_flipped');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card ADD card_flipped BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE account ADD password VARCHAR(255) NOT NULL');
    }
}
