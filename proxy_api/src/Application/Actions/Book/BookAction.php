<?php

namespace App\Application\Actions\Book;

use App\Application\Domain\Book;
use App\Application\Dto\Book\BookDto;
use App\Application\Dto\Book\BookImageDto;
use DI\NotFoundException;

class BookAction extends BaseBookAction
{
    # Task was return certain book with that isbn, so we need it here
    const DEFAULT_BOOK_ISBN = '9783442236862';

    protected function getDataForResponse(): object
    {
        $isbn = isset($this->args['isbn']) ? (string)$this->args['isbn'] : self::DEFAULT_BOOK_ISBN;
        if (!$bookData = $this->openLibraryRepository->findBook($isbn)) {
            throw new NotFoundException("Book ISBN:$isbn not found!");
        }

        return $this->createBookDto($bookData);
    }

    /**
     * @throws \ReflectionException
     */
    private function createBookDto(array $bookData): BookDto
    {
        $book = $this->getBookFromApiData($bookData);
        $bookImage = new BookImageDto($book->thumbnailUrl, $book->largeImageUrl);

        return new BookDto(
            $book->title,
            array_column($book->authors, 'name'),
            $this->getCFormattedDate($book->publishDate),
            $book->physicalFormat,
            $bookImage
        );
    }

    /**
     * @throws \ReflectionException
     */
    private function getBookFromApiData(array $bookData): Book
    {
        $book = new Book();
        $this->openLibraryDataMapperRepository->fillObject($book, $bookData);

        return $book;
    }
}