<?php

namespace App\Application\Repositories;

use App\Application\Dto\Book\BookListItemDto;

class BooksListSortingRepository
{
    const SORT_BY_TITLE = 'title';
    const SORT_BY_AUTHOR = 'author';
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    public function __construct(
        private string $sort = self::SORT_ASC,
        private string $sortBy = self::SORT_BY_TITLE
    )
    {
    }

    /**
     * @param BookListItemDto[] $bookList
     * @return \App\Application\Dto\Book\BookListItemDto[]
     */
    public function sort(array $bookList): array
    {
        if ($this->sortBy === self::SORT_BY_AUTHOR) {
            usort($bookList, [$this, 'sortByAuthor']);
        }

        if ($this->sortBy === self::SORT_BY_TITLE) {
            usort($bookList, [$this, 'sortByTitle']);
        }

        if ($this->sort === self::SORT_DESC) {
            $bookList = array_reverse($bookList);
        }

        return $bookList;
    }

    /**
     * @param string $sort
     * @return \App\Application\Repositories\BooksListSortingRepository
     * @throws \InvalidArgumentException
     */
    public function setSort(string $sort): static
    {
        if (!$this->checkSort($sort)) {
            throw new \InvalidArgumentException('Invalid sort argument');
        }

        $this->sort = $sort;
        return $this;
    }

    private function checkSort(string $sort): bool
    {
        return in_array($sort, [self::SORT_ASC, self::SORT_DESC]);
    }

    /**
     * @param string $sortBy
     * @return \App\Application\Repositories\BooksListSortingRepository
     * @throws \InvalidArgumentException
     */
    public function setSortBy(string $sortBy): static
    {
        if (!$this->checkSortBy($sortBy)) {
            throw new \InvalidArgumentException('Invalid sortBy argument');
        }

        $this->sortBy = $sortBy;
        return $this;
    }

    private function checkSortBy(string $sortBy): bool
    {
        return in_array($sortBy, [self::SORT_BY_AUTHOR, self::SORT_BY_TITLE]);
    }

    private function sortByTitle(BookListItemDto $a, BookListItemDto $b): int
    {
        return strcmp($a->title, $b->title);
    }

    private function sortByAuthor(BookListItemDto $a, BookListItemDto $b): int
    {
        return strcmp(
            ...array_map(
                function (BookListItemDto $bookListItem) {
                    $authors = $bookListItem->authors;
                    asort($authors);
                    return current($authors);
                },
                [$a, $b]
            )
        );
    }
}