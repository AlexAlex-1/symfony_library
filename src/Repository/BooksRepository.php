<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Books>
 *
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, Books::class);
    }

    public function delete(Books $book): void
    {
        $sql = 'CALL delete_book(:book_id)';
        $params = array('book_id' => $book->getId());

        $this->executeSqlProcedure($sql, $params);
    }

    public function create(Books $book): void
    {
        $sql = 'CALL add_book(:book_title, :book_author, :book_release_year)';
        $params = array(
            'book_title' => $book->getTitle(),
            'book_author' => $book->getAuthor(),
            'book_release_year' => $book->getReleaseYear()
        );

        $this->executeSqlProcedure($sql, $params);
    }

    public function update(Books $book): void
    {
        $sql = 'CALL edit_book(:book_id, :book_title, :book_author, :book_release_year)';

        $params = array(
            'book_id' => $book->getId(),
            'book_title' => $book->getTitle(),
            'book_author' => $book->getAuthor(),
            'book_release_year' => $book->getReleaseYear()
        );

        $this->executeSqlProcedure($sql, $params);
    }

    public function getById(int $id): Books
    {
        $book = $this->findOneBy(['id' => $id]);
        
        if (!$book) {
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Book with ID ' . $id . ' is not found.'
            );
        }

        return $book;
    }

    private function executeSqlProcedure(string $sql, array $params): void
    {
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
