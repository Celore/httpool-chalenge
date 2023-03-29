<?php

namespace App\Application\Domain;

class Author implements OpenLibraryValueObjectInterface
{
    /**
     * @open-library-json-field-name key
     * @var string
     */
    public string $key = '';
    /**
     * @open-library-json-field-name name
     * @var string
     */
    public string $name = '';
}