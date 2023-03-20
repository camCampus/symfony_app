<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320105515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__quack AS SELECT id, text, created_at FROM quack');
        $this->addSql('DROP TABLE quack');
        $this->addSql('CREATE TABLE quack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contend CLOB DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO quack (id, contend, created_at) SELECT id, text, created_at FROM __temp__quack');
        $this->addSql('DROP TABLE __temp__quack');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__quack AS SELECT id, contend, created_at FROM quack');
        $this->addSql('DROP TABLE quack');
        $this->addSql('CREATE TABLE quack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, text CLOB DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO quack (id, text, created_at) SELECT id, contend, created_at FROM __temp__quack');
        $this->addSql('DROP TABLE __temp__quack');
    }
}
