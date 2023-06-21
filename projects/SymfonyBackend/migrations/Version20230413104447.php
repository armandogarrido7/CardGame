<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413104447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Updated Player fields';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP CONSTRAINT fk_98197a65a76ed395');
        $this->addSql('DROP INDEX uniq_98197a65a76ed395');
        $this->addSql('ALTER TABLE player RENAME COLUMN user_id TO account_id');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A659B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A659B6B5FBA ON player (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A659B6B5FBA');
        $this->addSql('DROP INDEX UNIQ_98197A659B6B5FBA');
        $this->addSql('ALTER TABLE player RENAME COLUMN account_id TO user_id');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT fk_98197a65a76ed395 FOREIGN KEY (user_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_98197a65a76ed395 ON player (user_id)');
    }
}
