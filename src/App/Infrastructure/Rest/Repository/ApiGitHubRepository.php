<?php

namespace App\Infrastructure\Rest\Repository;
use App\Domain\Repository\ApiGitHubRepositoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class ApiGitHubRepository
 * @package App\Infrastructure\Rest\Repository
 */
class ApiGitHubRepository implements ApiGitHubRepositoryInterface
{
    /** @var ClientInterface $client */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $page
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLaravelRepos(int $page = 1): array
    {
        $response = $this->client->request('GET', '/users/laravel/repos', [
            'query' => [
                'page' => $page
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param int $page
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSymfonyRepos(int $page = 1): array
    {
        $response = $this->client->request('GET', '/users/symfony/repos', [
            'query' => [
                'page' => $page
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }
}
