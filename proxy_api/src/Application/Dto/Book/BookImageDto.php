<?php

namespace App\Application\Dto\Book;

class BookImageDto implements ImageInterface
{
    public function __construct(
        public readonly string $thumbnailUrl,
        public readonly string $largeImageUrl
    )
    {
    }
}