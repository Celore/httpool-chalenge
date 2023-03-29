<?php

namespace App\Application\Dto\Repositories;

class DataMapperFieldDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $class,
        public readonly bool $isArray,
    )
    {
    }
}