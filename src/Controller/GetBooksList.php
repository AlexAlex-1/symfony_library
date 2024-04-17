<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BooksRepository;

class GetBooksList extends AbstractController
{
    /**
     * @Route("/books", name="books_list", methods={"GET"})
     *
     * @param BooksRepository $booksRepository
     * @return JsonResponse
     */
    public function index(BooksRepository $booksRepository): JsonResponse
    {
        $booksList = array('books' => $booksRepository->findAll());

        return $this->json($booksList);
    }
}
