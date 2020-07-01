<?php
declare(strict_types=1);

namespace App\Infrastructure\Container\Application\Factory;

use App\Infrastructure\Rest\Repository\ApiGitHubRepository;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

class ApiGitHubRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     * @return ApiGitHubRepository
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $client = new Client($config['github_api']['http']);

        return new ApiGitHubRepository($client);
    }
}