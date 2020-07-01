<?php
declare(strict_types=1);

namespace App\Infrastructure\Container\Application\Factory;

use App\Application\Service\GitHubService;
use App\Domain\Entity\GitHubRepository;
use App\Domain\Entity\Owner;
use App\Domain\Repository\ApiGitHubRepositoryInterface;
use App\Domain\Repository\GitHubRepositoryInterface;
use App\Domain\Repository\OwnerRepositoryInterface;
use App\Infrastructure\Rest\Repository\ApiGitHubRepository;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

/**
 * Class GitHubServiceFactory
 * @package App\Infrastructure\Container\Application\Factory
 */
class GitHubServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $em */
        $em = $container->get('doctrine.entity_manager.orm_default');

        /** @var GitHubRepositoryInterface $repository */
        $repository = $em->getRepository(GitHubRepository::class);

        /** @var OwnerRepositoryInterface $ownerRepository */
        $ownerRepository = $em->getRepository(Owner::class);

        /** @var ApiGitHubRepositoryInterface $repository */
        $apiGitHubrepository = $container->get(ApiGitHubRepository::class);

        return new GitHubService(
            $repository,
            $ownerRepository,
            $apiGitHubrepository
        );
    }
}
