<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518122820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cards Inserted';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (1, 1, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (2, 2, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (3, 3, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (4, 4, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (5, 5, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (6, 6, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (7, 7, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (8, 8, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (9, 9, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (10, 10, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (11, 11, 'clubs')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (12, 12, 'clubs')");

        $this->addSql("INSERT INTO card(id, number, suit) VALUES (13, 1, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (14, 2, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (15, 3, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (16, 4, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (17, 5, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (18, 6, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (19, 7, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (20, 8, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (21, 9, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (22, 10, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (23, 11, 'cups')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (24, 12, 'cups')");

        $this->addSql("INSERT INTO card(id, number, suit) VALUES (25, 1, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (26, 2, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (27, 3, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (28, 4, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (29, 5, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (30, 6, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (31, 7, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (32, 8, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (33, 9, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (34, 10, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (35, 11, 'golds')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (36, 12, 'golds')");

        $this->addSql("INSERT INTO card(id, number, suit) VALUES (37, 1, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (38, 2, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (39, 3, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (40, 4, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (41, 5, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (42, 6, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (43, 7, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (44, 8, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (45, 9, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (46, 10, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (47, 11, 'swords')");
        $this->addSql("INSERT INTO card(id, number, suit) VALUES (48, 12, 'swords')");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE * FROM card');
    }
}
