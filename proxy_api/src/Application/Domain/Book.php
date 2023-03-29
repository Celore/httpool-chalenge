<?php

namespace App\Application\Domain;


class Book implements OpenLibraryValueObjectInterface
{
    /**
     * @open-library-json-field-name title
     * @var string
     */
    public string $title = '';
    /**
     * @open-library-json-field-name authors
     * @open-library-json-field-class \App\Application\Domain\Author
     * @var array<string, string>
     */
    public array $authors = [];
    /**
     * @open-library-json-field-name author_name
     * @var array<string>
     */
    public array $authorNames = [];
    /**
     * @open-library-json-field-name isbn_13
     * @var array
     */
    public array $isbn13 = [];
    /**
     * @open-library-json-field-name isbn_10
     * @var array
     */
    public array $isbn10 = [];
    /**
     * @open-library-json-field-name publish_date
     * @var string|null
     */
    public ?string $publishDate = null;
    /**
     * @open-library-json-field-name physical_format
     * @var string|null
     */
    public ?string $physicalFormat = null;
    /**
     * @open-library-json-field-name number_of_pages
     * @var int|null
     */
    public ?int $numberOfPages = null;
    /**
     * @open-library-json-field-name weight
     * @var string|null
     */
    public ?string $weight = null;
    /**
     * @open-library-json-field-name thumbnail_url
     * @var string
     */
    public string $thumbnailUrl = '';
    /**
     * @open-library-json-field-name preview_url
     * @var string|null
     */
    public ?string $largeImageUrl = null;
    /**
     * @open-library-json-field-name publishers
     */
    public ?array $publishers = null;
}