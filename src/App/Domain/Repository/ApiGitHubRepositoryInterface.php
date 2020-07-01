<?php

declare(strict_types=1);

namespace App\Domain\Repository;

interface ApiGitHubRepositoryInterface
{
    /**
     * @param int $page
     * @return array
     */
    public function getLaravelRepos(int $page): array;

    /**
     * @param int $page
     * @return array
     */
    public function getSymfonyRepos(int $page): array;
}
