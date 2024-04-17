<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractActionController
 */
class AbstractActionController extends AbstractController
{
    /**
     * Request $request
     * @return void
     */
    protected function validateRequest(Request $request): void
    {
        if (
            $request->getContentType() !== 'json'
            || !$request->getContent()
        ) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                'Request content is not JSON.'
            );
        }
    }

    /**
     * @param mixed $id
     * @return int
     */
    protected function validateId(mixed $id): int
    {
        try {
            $id = (int)$id;
        } catch (\Exception $e) {
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'The book ID must be integer ' . gettype($id) . ' passed.'
            );
        }

        return $id;
    }
}
