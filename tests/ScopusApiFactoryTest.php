<?php

namespace Scopus\Tests;

use PHPUnit\Framework\TestCase;
use Scopus\ScopusApiFactory;
use Scopus\ScopusApi;

class ScopusApiFactoryTest extends TestCase
{
    public function testCreateApiClient()
    {
        $factory = new ScopusApiFactory('fake-api-key');
        $api = $factory->createApiClient();
        
        $this->assertInstanceOf(ScopusApi::class, $api);
    }
}
