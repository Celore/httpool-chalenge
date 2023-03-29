<?php

namespace App\Application\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class OpenLibraryRepository
{
    const OPEN_LIBRARY_HOST = 'https://openlibrary.org';
    const OPEN_LIBRARY_BOOKS_HANDLER = '/api/books';
    const OPEN_LIBRARY_SEARCH_HANDLER = '/search.json';

    # Timeouts are too high, but it is not a real app
    const CONNECT_TIMEOUT = 10;
    const TIMEOUT = 10;

    private string $method = 'GET';
    private string $uri = '';

    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findBook(string $isbn): array
    {
        $this->uri = self::OPEN_LIBRARY_HOST . self::OPEN_LIBRARY_BOOKS_HANDLER;

        $response = $this->client->request(
            $this->method,
            $this->uri,
            array_merge(
                $this->getConnectionOptions(),
                [
                    RequestOptions::QUERY => [
                        'bibkeys' => "ISBN:$isbn",
                        'jscmd' => 'details',
                        'format' => 'json',
                    ]
                ]
            )
        );

        return $this->getDataArrayFromResponse($response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findBooks(?string $title, ?string $author, int $page, int $limit)
    {
        $this->uri = self::OPEN_LIBRARY_HOST . self::OPEN_LIBRARY_SEARCH_HANDLER;
        $response = $this->client->request(
            $this->method,
            $this->uri,
            array_merge(
                $this->getConnectionOptions(),
                [
                    RequestOptions::QUERY => array_filter([
                        'fields' => 'author_name,title,isbn',
                        'title' => $title,
                        'author' => $author,
                        'page' => $page,
                        'limit' => $limit
                    ])
                ]
            )
        );

        $data = $this->getDataArrayFromResponse($response);
        return $data['docs'] ?? [];
    }

    private function getConnectionOptions(): array
    {
        return [
            RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT,
            RequestOptions::TIMEOUT => self::TIMEOUT,
        ];
    }

    private function getDataArrayFromResponse(ResponseInterface $response)
    {
        $responseBody = (string)$response->getBody();
        $bookData = json_decode($responseBody, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $bookData;
        } else {
            throw new BadResponseException(
                "Got wrong json from Book API",
                new Request($this->method, $this->uri),
                $response
            );
        }
    }
}