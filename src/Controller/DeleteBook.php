<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BooksRepository;
use App\Entity\Books;

/**
 * Class DeleteBook
 */
class DeleteBook extends AbstractActionController
{
    /**
     * @Route("/book/{id}", name="delete_book", methods={"DELETE"})
     *
     * @param BooksRepository $booksRepository
     * @param mixed $id
     */
    public function index(
        BooksRepository $booksRepository,
        mixed $id
    ): JsonResponse {
        $id = $this->validateId($id);
        $book = $booksRepository->getById($id);

        $booksRepository->delete($book);

        return $this->json([
            'title' => 'Book has been deleted!',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
