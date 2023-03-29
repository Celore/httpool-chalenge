<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** @var \Slim\App $app */
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Main page");
    return $response;
});

$app->get('/book[/{isbn:[0-9]+}]', \App\Application\Actions\Book\BookAction::class);
$app->get('/books', \App\Application\Actions\Book\BooksAction::class);
