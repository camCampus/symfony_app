<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327125756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, quack_id_id, content, author, created_at FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quack_id_id INTEGER NOT NULL, duck_id INTEGER NOT NULL, content CLOB NOT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_9474526C9C388BCB FOREIGN KEY (quack_id_id) REFERENCES quack (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526C12CF4A47 FOREIGN KEY (duck_id) REFERENCES duck (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, quack_id_id, content, author, created_at) SELECT id, quack_id_id, content, author, created_at FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C9C388BCB ON comment (quack_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526C12CF4A47 ON comment (duck_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, quack_id_id, content, created_at, author FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quack_id_id INTEGER NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, author VARCHAR(255) NOT NULL, CONSTRAINT FK_9474526C9C388BCB FOREIGN KEY (quack_id_id) REFERENCES quack (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, quack_id_id, content, created_at, author) SELECT id, quack_id_id, content, created_at, author FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C9C388BCB ON comment (quack_id_id)');
    }
}
