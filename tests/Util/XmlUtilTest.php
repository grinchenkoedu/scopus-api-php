<?php

namespace Scopus\Tests\Util;

use PHPUnit\Framework\TestCase;
use Scopus\Util\XmlUtil;
use Scopus\Exception\XmlException;

class XmlUtilTest extends TestCase
{
    public function testXmlToArray()
    {
        $xmlString = '<root><child attr="value">text</child><empty/></root>';
        $xml = simplexml_load_string($xmlString);
        
        $array = XmlUtil::toArray($xml);
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('root', $array);
        $root = $array['root'];
        
        $this->assertArrayHasKey('child', $root);
        $this->assertEquals('text', $root['child']['$']);
        $this->assertEquals('value', $root['child']['@attr']);
        
        $this->assertArrayHasKey('empty', $root);
    }
}
