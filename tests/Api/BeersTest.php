<?php

namespace App\Tests\Api;

use App\Entity\Beer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BeersTest extends AbstractApiResource
{
    protected function getResourceClass(): string
    {
        return Beer::class;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetCollection(): void
    {
        static::createClient()->request('GET', '/api/beers');

        $this->assertGetCollectionResponse([
            'hydra:search' => [
                '@type' => 'hydra:IriTemplate',
                'hydra:template' => '/api/beers{?order[abv],order[ibu]}',
                'hydra:variableRepresentation' => 'BasicRepresentation',
                'hydra:mapping' => [
                    [
                        '@type' => 'IriTemplateMapping',
                        'variable' => 'order[abv]',
                        'property' => 'abv',
                        'required' => false
                    ],
                    [
                        '@type' => 'IriTemplateMapping',
                        'variable' => 'order[ibu]',
                        'property' => 'ibu',
                        'required' => false
                    ]
                ]
            ]
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetItem(): void
    {
        static::createClient()->request('GET', '/api/beers/1');

        $this->assertGetItemResponse([
            '@context' => '/api/contexts/Beer',
            '@id' => '/api/beers/1',
            '@type' => 'Beer',
            'id' => 1,
            'name' => 'Beer 1',
            'abv' => 5.5,
            'ibu' => 60,
            'brewery' => '/api/breweries/1'
        ]);
    }


    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testPostItem()
    {
        $data = [
            'name' => 'Beer 5',
            'abv' => 8.3,
            'ibu' => 30,
            'brewery' => '/api/breweries/3'
        ];

        static::createClient()->request('POST', '/api/beers', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPostItemResponse([
            '@context' => '/api/contexts/Beer',
            '@id' => '/api/beers/5',
            '@type' => 'Beer',
            'id' => 5,
            'name' => 'Beer 5',
            'abv' => 8.3,
            'ibu' => 30,
            'brewery' => '/api/breweries/3'
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testPostItemViolations()
    {
        static::createClient()->request('POST', '/api/beers', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode([])
        ]);

        $this->assertViolationsResponse([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: This value should not be blank.',
            'violations' => [
                [
                    'propertyPath' => 'name',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ]
            ]
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testPutItem()
    {
        $data = [
            'name' => 'Beer 5 - v2',
        ];

        static::createClient()->request('PUT', '/api/beers/5', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPutItemResponse([
            '@context' => '/api/contexts/Beer',
            '@id' => '/api/beers/5',
            '@type' => 'Beer',
            'id' => 5,
            'name' => 'Beer 5 - v2',
            'abv' => 8.3,
            'ibu' => 30,
            'brewery' => '/api/breweries/3'
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteItem()
    {
        static::createClient()->request('DELETE', '/api/beers/5');

        $this->assertDeleteItemResponse();
    }
}
