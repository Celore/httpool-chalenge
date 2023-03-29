<?php

namespace App\Application\Actions\Book;

use App\Application\Domain\Book;
use App\Application\Domain\ListingBook;
use App\Application\Dto\Book\BookImageDto;
use App\Application\Dto\Book\BookListItemDetailsDto;
use App\Application\Dto\Book\BookListItemDto;
use App\Application\Repositories\BooksListSortingRepository;
use App\Application\Repositories\OpenLibraryDataMapperRepository;
use App\Application\Repositories\OpenLibraryRepository;
use DI\NotFoundException;

class BooksAction extends BaseBookAction
{

    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    const ISBN_EDITIONS_COUNT = 5;

    /**
     * @var \App\Application\Repositories\BooksListSortingRepository
     */
    private BooksListSortingRepository $booksSortingRepository;

    public function __construct(
        OpenLibraryRepository           $openLibraryRepository,
        OpenLibraryDataMapperRepository $openLibraryDataMapperRepository,
        BooksListSortingRepository      $booksSortingRepository,

    )
    {
        parent::__construct(
            $openLibraryRepository,
            $openLibraryDataMapperRepository
        );
        $this->booksSortingRepository = $booksSortingRepository;
    }

    /**
     * @return array
     * @throws \DI\NotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */
    protected function getDataForResponse(): array
    {
        $this->setSortParams();

        $bookList = [];
        $foundedBooks = $this->getBooksSearchResult();

        foreach ($foundedBooks as $bookData) {
            $bookList[] = $this->createBookListItem($bookData);
        }

        return $this->booksSortingRepository->sort($bookList);
    }

    private function setSortParams()
    {
        if ($sortBy = $this->getQueryParam('sort_by')) {
            $this->booksSortingRepository->setSortBy($sortBy);
        }
        if ($sort = $this->getQueryParam('sort')) {
            $this->booksSortingRepository->setSort($sort);
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \DI\NotFoundException
     */
    private function getBooksSearchResult(): array
    {
        $booksData = $this->openLibraryRepository->findBooks(
            $this->getQueryParam('title'),
            $this->getQueryParam('author'),
            $this->getQueryParam('page', self::DEFAULT_PAGE),
            $this->getQueryParam('limit', self::DEFAULT_LIMIT)
        );
        if (!$booksData) {
            throw new NotFoundException("Books are not found!");
        }

        return $booksData;
    }

    /**
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function createBookListItem(array $bookData): BookListItemDto
    {
        $listingBook = new ListingBook();
        $this->openLibraryDataMapperRepository->fillObject($listingBook, $bookData);

        $bookEditions = $this->findBookEditions($listingBook);

        return new BookListItemDto($listingBook->title, $listingBook->authors, $bookEditions);
    }

    /**
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function findBookEditions(ListingBook $listingBook): array
    {
        $bookEditions = [];
        foreach ($listingBook->isbnCodes as $isbn) {
            if (count($bookEditions) >= self::ISBN_EDITIONS_COUNT) {
                break;
            }

            if ($bookEdition = $this->findBookEdition($isbn)) {
                $bookEditions[] = $bookEdition;
            }
        }

        return $bookEditions;
    }

    /**
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function findBookEdition(string $isbn): ?BookListItemDetailsDto
    {
        $bookData = $this->openLibraryRepository->findBook($isbn);
        if (!$bookData) {
            return null;
        }

        $book = new Book();
        $this->openLibraryDataMapperRepository->fillObject($book, $bookData);

        return new BookListItemDetailsDto(
            current($book->isbn13) ?: current($book->isbn10),
            $this->getCFormattedDate($book->publishDate),
            $book->physicalFormat,
            $book->numberOfPages,
            $book->weight,
            new BookImageDto(
                $book->thumbnailUrl, $book->largeImageUrl
            )
        );
    }
}