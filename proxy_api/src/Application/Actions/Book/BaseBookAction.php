<?php

namespace App\Application\Actions\Book;

use App\Application\Actions\Action;
use App\Application\Actions\ActionError;
use App\Application\Repositories\OpenLibraryDataMapperRepository;
use App\Application\Repositories\OpenLibraryRepository;
use DI\NotFoundException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseBookAction extends Action
{
    public function __construct(
        protected OpenLibraryRepository           $openLibraryRepository,
        protected OpenLibraryDataMapperRepository $openLibraryDataMapperRepository
    )
    {
    }

    protected function action(): Response
    {
        try {
            $data = $this->getDataForResponse();
        } catch (NotFoundException $e) {
            return $this->respondWithError(ActionError::RESOURCE_NOT_FOUND, $e->getMessage(), 400);
        } catch (GuzzleException $e) {
            return $this->respondWithError(ActionError::BAD_REQUEST, $e->getMessage(), 400);
        } catch (\ReflectionException $e) {
            return $this->respondWithError(ActionError::SERVER_ERROR, $e->getMessage(), 500);
        } catch (\InvalidArgumentException $e) {
            return $this->respondWithError(ActionError::INVALID_ARGUMENT, $e->getMessage(), 500);
        }

        return $this->respondJson($data);
    }

    /**
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    abstract protected function getDataForResponse(): array|object;

    protected function getCFormattedDate(?string $publishDate): ?string
    {
        if (is_null($publishDate)) {
            return null;
        }
        
        try {
            $dateTime = (new \DateTime($publishDate))->format('c');
        } catch (\Exception $e) {
            $dateTime = null;
        }

        return $dateTime;
    }

    protected function respondWithError(string $type, string $description, int $statusCode = 200): ResponseInterface
    {
        $error = new ActionError($type, $description);

        return $this->respondWithData($error, $statusCode);
    }
}