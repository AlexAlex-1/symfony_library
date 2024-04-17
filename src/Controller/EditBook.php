<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BooksRepository;

/**
 * Class EditBook
 */
class EditBook extends AbstractActionController
{
    /**
     * @Route("/book/{id}", name="edit_book", methods={"PUT"})
     *
     * @param Request $request
     * @param BooksRepository $booksRepository
     * @param mixed $id
     * @return JsonResponse
     */
    public function index(
        Request $request,
        BooksRepository $booksRepository,
        mixed $id
    ): JsonResponse {
        $this->validateRequest($request);

        $id = $this->validateId($id);
        $requestFields = $request->toArray();

        $book = $booksRepository->getById($id);

        foreach ($requestFields as $fieldName => $fieldValue) {
            $book->setData($fieldName, $fieldValue);
        }
        $book->setData('id', $id);

        $booksRepository->update($book);
        
        return $this->json([
            'title' => 'Book has been edited!',
            'status' => Response::HTTP_ACCEPTED
        ], Response::HTTP_ACCEPTED);
    }
}
