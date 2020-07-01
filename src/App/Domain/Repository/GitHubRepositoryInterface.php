<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\GitHubRepository;

interface GitHubRepositoryInterface
{
    /**
     * @param int $id
     * @return GitHubRepository|null
     */
    public function fromId(int $id): ?GitHubRepository;

    /**
     * @param int $offset
     * @param int $limit
     * @param bool $exportCsv
     * @return array
     */
    public function getRepositories(int $offset, int $limit, bool $exportCsv): array;

    /**
     * @param GitHubRepository $repo
     */
    public function save(GitHubRepository $repo): void;
}
