<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

abstract class AbstractApiResource extends ApiTestCase
{
    abstract protected function getResourceClass(): string;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function assertSuccessfulResponse(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function assertGetCollectionResponse(?array $jsonContains = []): void
    {
        $this->assertSuccessfulResponse();
        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesResourceCollectionJsonSchema($this->getResourceClass());
        $this->assertJsonContains($jsonContains);
        $this->assertMatchesJsonSchema([
            'type' => 'object',
            'properties' => [
                '@context' => ['type' => 'string'],
                '@id' => ['type' => 'string'],
                '@type' => ['type' => 'string'],
                'hydra:totalItems' => ['type' => 'integer'],
                'hydra:member' => ['type' => 'array'],
            ],
            'required' => ['@context', '@id', '@type', 'hydra:totalItems', 'hydra:member']
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function assertGetItemResponse(array $jsonContains): void
    {
        $this->assertSuccessfulResponse();
        $this->assertResponseStatusCodeSame(200);
        $this->assertResourceContentResponse($jsonContains);;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function assertPostItemResponse(array $jsonContains): void
    {
        $this->assertSuccessfulResponse();
        $this->assertResponseStatusCodeSame(201);
        $this->assertResourceContentResponse($jsonContains);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function assertViolationsResponse(array $jsonContains): void
    {
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains($jsonContains);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function assertPutItemResponse(array $jsonContains): void
    {
        $this->assertSuccessfulResponse();
        $this->assertResponseStatusCodeSame(200);
        $this->assertResourceContentResponse($jsonContains);
    }

    protected function assertDeleteItemResponse(): void
    {
        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function assertResourceContentResponse(array $jsonContains): void
    {
        $this->assertMatchesResourceItemJsonSchema($this->getResourceClass());
        $this->assertJsonContains($jsonContains);
        $this->assertMatchesJsonSchema([
            'type' => 'object',
            'properties' => [
                'createdAt' => ['type' => 'string', 'format' => 'date-time'],
                'updatedAt' => ['type' => 'string', 'format' => 'date-time'],
            ],
            'required' => ['createdAt', 'updatedAt']
        ]);
    }
}
