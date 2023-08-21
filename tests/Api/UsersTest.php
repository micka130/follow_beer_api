<?php

namespace App\Tests\Api;

use App\Entity\User;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UsersTest extends AbstractApiResource
{
    protected function getResourceClass(): string
    {
        return User::class;
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
        static::createClient()->request('GET', '/api/users');

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
        static::createClient()->request('GET', '/api/users/1');

        $this->assertGetItemResponse([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/1',
            '@type' => 'User',
            'id' => 1,
            'email' => 'user1@acme.fr',
            'password' => 'afo86jbdxdh5gp5j',
            'username' => 'user1',
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
            'email' => 'user4@acme.fr',
            'password' => 'etxf6kjqfmA8rd3o',
            'username' => 'user4',
        ];

        static::createClient()->request('POST', '/api/users', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPostItemResponse([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/4',
            '@type' => 'User',
            'id' => 4,
            'email' => 'user4@acme.fr',
            'password' => 'etxf6kjqfmA8rd3o',
            'username' => 'user4',
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
        static::createClient()->request('POST', '/api/users', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode([])
        ]);

        $this->assertViolationsResponse([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'email: This value should not be blank.
password: This value should not be blank.
username: This value should not be blank.',
            'violations' => [
                [
                    'propertyPath' => 'email',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ],
                [
                    'propertyPath' => 'password',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ],
                [
                    'propertyPath' => 'username',
                    'message' => 'This value should not be blank.',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3'
                ],
            ]
        ]);
        static::createClient()->request('POST', '/api/users', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode([
                'email' => 'user3@acme.fr',
                'password' => 'etxf6kjqfmA8rd3o',
                'username' => 'user3',
            ])
        ]);

        $this->assertViolationsResponse([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'email: This value is already used.
username: This value is already used.',
            'violations' => [
                [
                    'propertyPath' => 'email',
                    'message' => 'This value is already used.',
                    'code' => '23bd9dbf-6b9b-41cd-a99e-4844bcf3077f'
                ],
                [
                    'propertyPath' => 'username',
                    'message' => 'This value is already used.',
                    'code' => '23bd9dbf-6b9b-41cd-a99e-4844bcf3077f'
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
            'email' => 'user4.v2@acme.fr',
        ];

        static::createClient()->request('PUT', '/api/users/4', [
            'headers' => [
                'Content-type' => 'application/ld+json'
            ],
            'body' => json_encode($data)
        ]);

        $this->assertPutItemResponse([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/4',
            '@type' => 'User',
            'id' => 4,
            'email' => 'user4.v2@acme.fr',
            'password' => 'etxf6kjqfmA8rd3o',
            'username' => 'user4',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteItem()
    {
        static::createClient()->request('DELETE', '/api/users/4');

        $this->assertDeleteItemResponse();
    }
}
