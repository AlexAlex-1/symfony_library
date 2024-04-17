<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BooksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints\ValidYear;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    public const REQUIRED_FIELDS = array(
        'title',
        'author',
        'release_year'
    );

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    /**
     * @ValidYear
     */
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $release_year = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->release_year;
    }

    public function setReleaseYear(int $release_year): static
    {
        $this->release_year = $release_year;

        return $this;
    }

    /**
     * This function allows to set data by field IDs
     *
     * @param string $fieldName
     * @param mixed $value
     */
    public function setData(string $fieldName, mixed $value): static
    {
        try {
            $this->$fieldName = $value;
        } catch (\TypeError $e) {
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "The $fieldName field has unsupported data type."
            );
        }

        return $this;
    }
}
