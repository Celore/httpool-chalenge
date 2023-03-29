<?php

namespace Tests\Application\Actions\Book;

use Tests\TestCase;

class BookActionTest extends TestCase
{
    public function testAction() {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/book');
        $response = $app->handle($request);

        $this->assertEquals($response->getStatusCode(), 200);
    }
}