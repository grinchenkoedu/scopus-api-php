<?php

namespace Scopus\Tests\Response;

use Scopus\Tests\TestCase;
use Scopus\Response\Author;
use Scopus\Response\AuthorProfile;
use Scopus\Response\Affiliation;
use Scopus\Response\AuthorName;

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
        $this->assertSame([], $author->getAffiliationHistory());
        $this->assertSame([], $author->getSubjectAreas());
    }

    public function testNameVariantCollections()
    {
        $affiliation = new Affiliation([
            'name-variant' => [['$' => 'Univ of Testing'], ['$' => 'Testing University']],
        ]);
        $this->assertSame(['Univ of Testing', 'Testing University'], $affiliation->getNameVariant());
        $this->assertSame([], (new Affiliation([]))->getNameVariant());

        $profile = new AuthorProfile([
            'name-variant' => [['indexed-name' => 'Doe J.'], ['indexed-name' => 'Doe Jane']],
        ]);
        $variants = $profile->getNameVariants();
        $this->assertCount(2, $variants);
        $this->assertInstanceOf(AuthorName::class, $variants[0]);
        $this->assertSame('Doe J.', $variants[0]->getIndexedName());

        // No name-variant at all still yields an array, not null.
        $this->assertSame([], (new AuthorProfile([]))->getNameVariants());
    }

    public function testAuthorProfileGettersWithSparseData()
    {
        $author = new Author(['author-profile' => []]);

        $profile = $author->getProfile();
        $this->assertInstanceOf(AuthorProfile::class, $profile);
        $this->assertNull($profile->getPreferredName());
        $this->assertSame([], $profile->getJournalHistory());
    }
}
