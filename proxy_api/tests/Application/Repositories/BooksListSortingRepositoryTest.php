<?php

namespace Tests\Application\Repositories;

use App\Application\Dto\Book\BookListItemDto;
use App\Application\Repositories\BooksListSortingRepository;
use PHPUnit\Framework\TestCase;

/**
 * @property \App\Application\Repositories\BooksListSortingRepository $repository
 */
class BooksListSortingRepositoryTest extends TestCase
{
    public function sortByRightValues(): array
    {
        return [
            ['value' => BooksListSortingRepository::SORT_BY_TITLE],
            ['value' => BooksListSortingRepository::SORT_BY_AUTHOR],
        ];
    }

    public function sortRightValues(): array
    {
        return [
            ['value' => BooksListSortingRepository::SORT_ASC],
            ['value' => BooksListSortingRepository::SORT_DESC],
        ];
    }

    public function bookList(): array
    {
        return [
            [
                'bookList' => [
                    new BookListItemDto('One book', ['Rowling', 'Church', 'God'], []),
                    new BookListItemDto('Another book', ['Somebody'], []),
                    new BookListItemDto('Third book', ['Somebody'], []),
                    new BookListItemDto('First book', ['Archer'], []),
                ]
            ]
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BooksListSortingRepository();
    }

    /**
     * @dataProvider sortByRightValues
     */
    public function testSetSortByRightValues(string $value)
    {
        $this->expectNotToPerformAssertions();
        $this->repository->setSortBy($value);
    }

    public function testSetSortByWrongValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->setSortBy('WRONG_VALUE');
    }

    /**
     * @dataProvider sortRightValues
     */
    public function testSetSortRightValues($value)
    {
        $this->expectNotToPerformAssertions();
        $this->repository->setSort($value);
    }

    /**
     * @dataProvider sortRightValues
     */
    public function testSetSortWrongValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->setSort('WRONG_VALUE');
    }

    /**
     * @dataProvider bookList
     */
    public function testSortByTitle(array $bookList)
    {
        $bookTitles = array_column($bookList, 'title');
        $ascSortedBookTitles = $this->getAscSortedArray($bookTitles);
        $descSortedBookTitles = array_reverse($ascSortedBookTitles);

        $this->repository->setSortBy(BooksListSortingRepository::SORT_BY_TITLE);
        $this->repository->setSort(BooksListSortingRepository::SORT_ASC);
        $sortedBookList = $this->repository->sort($bookList);

        $this->assertEquals($ascSortedBookTitles, array_column($sortedBookList, 'title'));

        $this->repository->setSort(BooksListSortingRepository::SORT_DESC);
        $sortedBookList = $this->repository->sort($bookList);

        $this->assertEquals($descSortedBookTitles, array_column($sortedBookList, 'title'));
    }

    private function getAscSortedArray(array $array): array
    {
        asort($array);
        return array_values($array);
    }
}
