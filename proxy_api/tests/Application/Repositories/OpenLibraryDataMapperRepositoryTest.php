<?php

namespace Tests\App\Application\Repositories;

use App\Application\Domain\Author;
use App\Application\Domain\OpenLibraryValueObjectInterface;
use App\Application\Repositories\OpenLibraryDataMapperRepository;
use PHPUnit\Framework\TestCase;

/**
 * @property \App\Application\Repositories\OpenLibraryDataMapperRepository $repository
 */
class OpenLibraryDataMapperRepositoryTest extends TestCase
{
    public function getOpenLibraryData(): array
    {
        return [
            [
                'testData' => [
                    'full_name' => 'Eugene Yu',
                    'age_count' => 26,
                    'authors' => [
                        [
                            'key' => 'key453465',
                            'name' => 'Author 1'
                        ],
                        [
                            'key' => 'key2354676',
                            'name' => 'Author 2'
                        ],
                    ],
                    'private_prop_value' => 60
                ]
            ],
            [
                'testData' => [
                    'age_count' => 50,
                    'authors' => [
                        [
                            'key' => '44444',
                            'name' => 'Author 4'
                        ],
                        [
                            'key' => '55555',
                            'name' => 'Author 5'
                        ],
                    ]
                ]
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new OpenLibraryDataMapperRepository();
    }

    /**
     * @dataProvider getOpenLibraryData
     */
    public function testFillObject(array $testData)
    {
        $class = new class implements OpenLibraryValueObjectInterface {
            /**
             * @open-library-json-field-name full_name
             * @var string
             */
            public string $name = '';
            /**
             * @open-library-json-field-name age_count
             * @var int
             */
            public int $age = 0;
            /**
             * @open-library-json-field-name empty_prop
             * @var int
             */
            public int $emptyPropInJsonData = 0;
            /**
             * @open-library-json-field-name private_prop_value
             * @var int
             */
            private int $privatePropertyShouldBeIgnored = 0;
            /**
             * @var string
             */
            public string $notFillingProp = 'some value';
            /**
             * @open-library-json-field-name authors
             * @open-library-json-field-class \App\Application\Domain\Author
             * @var array|string
             */
            public array|string $authors = [];
        };

        $exemplar = new $class();
        $exemplar->name = $testData['full_name'] ?? '';
        $exemplar->age = $testData['age_count'];
        $exemplar->authors = array_map(function ($item) {
            $author = new Author();
            $author->name = $item['name'];
            $author->key = $item['key'];
            return $author;
        }, $testData['authors']);

        $exemplarForRepository = new $class();
        $this->repository->fillObject($exemplarForRepository, $testData);

        $this->assertEquals($exemplar, $exemplarForRepository);
    }
}
