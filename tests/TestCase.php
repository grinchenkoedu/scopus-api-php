<?php

namespace Scopus\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Scopus\ScopusApi;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param \GuzzleHttp\Promise\PromiseInterface|\Psr\Http\Message\ResponseInterface|\Exception[] $mockResponses
     * @return ScopusApi
     */
    protected function getMockedApi(array $mockResponses): ScopusApi
    {
        $mock = new MockHandler($mockResponses);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        return new ScopusApi($client);
    }
}
