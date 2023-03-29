<?php

namespace App\Application\Domain;

class ListingBook implements OpenLibraryValueObjectInterface
{

    /**
     * @open-library-json-field-name title
     * @var string
     */
    public string $title;
    /**
     * @open-library-json-field-name author_name
     * @var string[]
     */
    public array $authors;
    /**
     * @var \App\Application\Domain\Book[]
     */
    public array $details = [];
    /**
     * @open-library-json-field-name isbn
     * @var array
     */
    public array $isbnCodes = [];
}