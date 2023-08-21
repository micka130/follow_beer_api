<?php

namespace App\Tests\Api;

use App\Entity\Brewery;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BreweriesTest extends AbstractApiResource
{
    protected function getResourceClass(): string
    {
        return Brewery::class;
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
        static::createClient()->request('GET', '/api/breweries');

        $this->assertGetCollectionResponse();
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
        static::createClient()->request('GET', '/api/breweries/1');

        $this->assertGetItemResponse([
            '@context' => '/api/contexts/Brewery',
            '@id' => '/api/breweries/1',
            '@type' => 'Brewery',
            'id' => 1,
            'name' => 'Brewery 1',
            'beers' => [
                '/api/beers/1',
                '/api/beers/2'
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
    public function testPostItem()
    {
        $data = [
            'name' => 'Brewery 4',
            'street' => '4 street',
            'city' => 'LA',
            'country' => 'United States',
        ];

        static::createClient()->request('POST', '/api/breweries', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPostItemResponse([
            '@context' => '/api/contexts/Brewery',
            '@id' => '/api/breweries/4',
            '@type' => 'Brewery',
            'id' => 4,
            'name' => 'Brewery 4',
            'street' => '4 street',
            'city' => 'LA',
            'country' => 'United States',
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
        static::createClient()->request('POST', '/api/breweries', [
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
            'name' => 'Brewery 4 - v2',
        ];

        static::createClient()->request('PUT', '/api/breweries/4', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPutItemResponse([
            '@context' => '/api/contexts/Brewery',
            '@id' => '/api/breweries/4',
            '@type' => 'Brewery',
            'id' => 4,
            'name' => 'Brewery 4 - v2',
            'street' => '4 street',
            'city' => 'LA',
            'country' => 'United States',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteItem()
    {
        static::createClient()->request('DELETE', '/api/breweries/4');

        $this->assertDeleteItemResponse();
    }
}
