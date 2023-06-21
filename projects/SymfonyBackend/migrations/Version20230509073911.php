<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509073911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modified Game attributes';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP playing');
        $this->addSql('ALTER TABLE game DROP finished');
        $this->addSql('ALTER TABLE game DROP started');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD finished BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE game ADD started BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE account ADD playing BOOLEAN NOT NULL');
    }
}
