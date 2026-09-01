<?php

namespace Scopus\Tests\Response;

use PHPUnit\Framework\TestCase;
use Scopus\Response\Author;
use Scopus\Response\AuthorProfile;

class AuthorTest extends TestCase
{
    public function testAuthorGetters()
    {
        $mockData = [
            'coredata' => [
                'dc:identifier' => 'AUTHOR_ID:123',
                'document-count' => '42',
                'cited-by-count' => '100'
            ],
            'author-profile' => [
                'preferred-name' => [
                    'surname' => 'Doe',
                    'given-name' => 'Jane',
                    'initials' => 'J.D.',
                    'indexed-name' => 'Doe J.'
                ]
            ]
        ];

        $author = new Author($mockData);

        $this->assertEquals('AUTHOR_ID:123', $author->getCoredata()->getIdentifier());
        $this->assertEquals('42', $author->getCoredata()->getDocumentCount());
        $this->assertEquals('100', $author->getCoredata()->getCitedByCount());
        
        $profile = $author->getProfile();
        $this->assertInstanceOf(AuthorProfile::class, $profile);
        $this->assertEquals('Doe', $profile->getPreferredName()->getSurname());
        $this->assertEquals('Jane', $profile->getPreferredName()->getGivenName());
    }

    public function testAuthorGettersWithSparseData()
    {
        $author = new Author([]);

        $this->assertNull($author->getCoredata());
        $this->assertNull($author->getProfile());
        $this->assertNull($author->getAffiliation());
        $this->assertNull($author->getAffiliationHistory());
        $this->assertNull($author->getSubjectAreas());
    }

    public function testAuthorProfileGettersWithSparseData()
    {
        $author = new Author(['author-profile' => []]);

        $profile = $author->getProfile();
        $this->assertInstanceOf(AuthorProfile::class, $profile);
        $this->assertNull($profile->getPreferredName());
        $this->assertNull($profile->getJournalHistory());
    }
}
