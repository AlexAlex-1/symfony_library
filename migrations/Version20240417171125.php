<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417171125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, release_year SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addProcedureToAddBook();
        $this->addProceduresToEditBook();
        $this->addProceduresToDeleteBook();
    }

    private function addProcedureToAddBook(): void
    {
        $this->addSql('
            CREATE PROCEDURE add_book(
                IN book_title VARCHAR(255),
                IN book_author VARCHAR(255),
                IN book_release_year SMALLINT
            )
            BEGIN
                INSERT INTO books (id, title, author, release_year)
                VALUES (NULL, book_title, book_author, book_release_year);
            END
        ');
    }

    private function addProceduresToEditBook(): void
    {
        $this->addSql('
            CREATE PROCEDURE edit_book(
                IN book_id INT,
                IN book_title VARCHAR(255),
                IN book_author VARCHAR(255),
                IN book_release_year SMALLINT
            )
            BEGIN
                UPDATE books SET
                    title = book_title,
                    author = book_author,
                    release_year = book_release_year
                WHERE id = book_id;
            END
        ');
    }

    private function addProceduresToDeleteBook(): void
    {
        $this->addSql('
            CREATE PROCEDURE delete_book(IN book_id INT)
            BEGIN
                DELETE FROM books WHERE id = book_id;
            END
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP PROCEDURE IF EXISTS add_book');
        $this->addSql('DROP PROCEDURE IF EXISTS edit_book');
        $this->addSql('DROP PROCEDURE IF EXISTS delete_book');
    }
}
