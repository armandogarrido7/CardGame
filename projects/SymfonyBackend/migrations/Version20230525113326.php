<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525113326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Some entities changed';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_5e89deba4acc9a20');
        $this->addSql('CREATE INDEX IDX_5E89DEBA4ACC9A20 ON play (card_id)');
        $this->addSql('ALTER TABLE player DROP alive');
        $this->addSql('ALTER TABLE round ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5EEEA34E48FD905 ON round (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_5E89DEBA4ACC9A20');
        $this->addSql('CREATE UNIQUE INDEX uniq_5e89deba4acc9a20 ON play (card_id)');
        $this->addSql('ALTER TABLE player ADD alive BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA34E48FD905');
        $this->addSql('DROP INDEX IDX_C5EEEA34E48FD905');
        $this->addSql('ALTER TABLE round DROP game_id');
    }
}
