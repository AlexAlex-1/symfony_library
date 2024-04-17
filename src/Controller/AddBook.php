<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BooksRepository;
use App\Entity\BooksFactory;

/**
 * Class AddBook
 */
class AddBook extends AbstractActionController
{
    /**
     * @Route("/book", name="add_book", methods={"POST"})
     *
     * @param ValidatorInterface $validator
     * @param BooksRepository $booksRepository
     * @param BooksFactory $booksFactory
     * @param Request $request
     * @return JsonResponse
     */
    public function index(
        ValidatorInterface $validator,
        BooksRepository $booksRepository,
        BooksFactory $booksFactory,
        Request $request
    ): JsonResponse {
        $this->validateRequest($request);

        $book = $booksFactory->create();
        $requestFields = $request->toArray();

        foreach ($requestFields as $fieldName => $fieldValue) {
            $book->setData($fieldName, $fieldValue);
        }

        $errors = $validator->validate($book);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                throw new HttpException(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    'Field: \'' . $error->getPropertyPath()
                    . '\' Error: ' .  $error->getMessage()
                );
            }
        }

        $booksRepository->create($book);

        return $this->json([
            'title' => 'Book has been created!',
            'status' => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }
}
