<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322082900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__duck AS SELECT id, email, roles, password, first_name, last_name FROM duck');
        $this->addSql('DROP TABLE duck');
        $this->addSql('CREATE TABLE duck (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, first_name VARCHAR(180) NOT NULL, last_name VARCHAR(180) NOT NULL, duck_name VARCHAR(180) NOT NULL)');
        $this->addSql('INSERT INTO duck (id, email, roles, password, first_name, last_name) SELECT id, email, roles, password, first_name, last_name FROM __temp__duck');
        $this->addSql('DROP TABLE __temp__duck');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_538A9547E7927C74 ON duck (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_538A95471E7F0B13 ON duck (duck_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__duck AS SELECT id, email, roles, password, first_name, last_name FROM duck');
        $this->addSql('DROP TABLE duck');
        $this->addSql('CREATE TABLE duck (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, first_name VARCHAR(180) NOT NULL, last_name VARCHAR(180) NOT NULL)');
        $this->addSql('INSERT INTO duck (id, email, roles, password, first_name, last_name) SELECT id, email, roles, password, first_name, last_name FROM __temp__duck');
        $this->addSql('DROP TABLE __temp__duck');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_538A9547E7927C74 ON duck (email)');
    }
}
