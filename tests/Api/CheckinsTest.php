<?php

namespace App\Tests\Api;

use App\Entity\Checkin;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CheckinsTest extends AbstractApiResource
{
    protected function getResourceClass(): string
    {
        return Checkin::class;
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
        static::createClient()->request('GET', '/api/checkins');

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
        static::createClient()->request('GET', '/api/checkins/1');

        $this->assertGetItemResponse([
            '@context' => '/api/contexts/Checkin',
            '@id' => '/api/checkins/1',
            '@type' => 'Checkin',
            'id' => 1,
            'score' => 5.8,
            'beer' => '/api/beers/1',
            'user' => '/api/users/1',
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
            'score' => 7.7,
            'beer' => '/api/beers/1',
            'user' => '/api/users/1',
        ];

        static::createClient()->request('POST', '/api/checkins', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPostItemResponse([
            '@context' => '/api/contexts/Checkin',
            '@id' => '/api/checkins/5',
            '@type' => 'Checkin',
            'id' => 5,
            'score' => 7.7,
            'beer' => '/api/beers/1',
            'user' => '/api/users/1',
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
        static::createClient()->request('POST', '/api/checkins', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode([])
        ]);

        $this->assertViolationsResponse([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'score: This value should not be blank.
beer: This value should not be blank.
user: This value should not be blank.',
            'violations' => [
                [
                    'propertyPath' => 'score',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ],
                [
                    'propertyPath' => 'beer',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ],
                [
                    'propertyPath' => 'user',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ]
            ]
        ]);

        static::createClient()->request('POST', '/api/checkins', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode([
                'score' => 11,
                'beer' => '/api/beers/1',
                'user' => '/api/users/1',
            ])
        ]);

        $this->assertViolationsResponse([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'score: This value should be less than or equal to 10.',
            'violations' => [
                [
                    'propertyPath' => 'score',
                    'message' => 'This value should be less than or equal to 10.',
                    'code' => '30fbb013-d015-4232-8b3b-8f3be97a7e14'
                ],
            ]
        ]);

        static::createClient()->request('POST', '/api/checkins', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode([
                'score' => -1,
                'beer' => '/api/beers/1',
                'user' => '/api/users/1',
            ])
        ]);

        $this->assertViolationsResponse([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'score: This value should be greater than or equal to 0.',
            'violations' => [
                [
                    'propertyPath' => 'score',
                    'message' => 'This value should be greater than or equal to 0.',
                    'code' => 'ea4e51d1-3342-48bd-87f1-9e672cd90cad'
                ],
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
            'score' => 8.8,
        ];

        static::createClient()->request('PUT', '/api/checkins/5', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPutItemResponse([
            '@context' => '/api/contexts/Checkin',
            '@id' => '/api/checkins/5',
            '@type' => 'Checkin',
            'id' => 5,
            'score' => 8.8,
            'beer' => '/api/beers/1',
            'user' => '/api/users/1',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteItem()
    {
        static::createClient()->request('DELETE', '/api/checkins/5');

        $this->assertDeleteItemResponse();
    }
}
