<?php
declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\GitHubRepository;
use App\Domain\Entity\Owner;
use App\Domain\Repository\ApiGitHubRepositoryInterface;
use App\Domain\Repository\GitHubRepositoryInterface;
use App\Domain\Repository\OwnerRepositoryInterface;

final class GitHubService
{
    /** @var GitHubRepositoryInterface */
    private $repository;

    /** @var OwnerRepositoryInterface */
    private $ownerRepository;

    /** @var ApiGitHubRepositoryInterface $repository */
    private $apiGitHubRepository;

    public function __construct(
        GitHubRepositoryInterface $repository,
        OwnerRepositoryInterface $ownerRepository,
        ApiGitHubRepositoryInterface $apiGitHubRepository
    ) {
        $this->repository = $repository;
        $this->ownerRepository = $ownerRepository;
        $this->apiGitHubRepository = $apiGitHubRepository;
    }

    /**
     * @throws \Exception
     */
    public function import()
    {
        $this->importSymfonyRepos();
        $this->importLaravelRepos();

        return ['message' => 'All repos were imported.'];
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param bool $exportCsv
     * @return array
     */
    public function getRepositories(int $offset, int $limit, bool $exportCsv)
    {
        return $this->repository->getRepositories($offset, $limit, $exportCsv);
    }

    /**
     * @param $ownerData
     * @return Owner
     */
    private function getOrInsertOwner($ownerData)
    {
        /** @var Owner $owner */
        $owner = $this->ownerRepository->fromId($ownerData['id']);

        if ($owner === null) {
            $owner = new Owner(
                $ownerData['id'],
                $ownerData['login']
            );

            $this->ownerRepository->save($owner);
        }

        return $owner;
    }

    /**
     * @param int $page
     * @throws \Exception
     */
    private function importSymfonyRepos($page = 1): void
    {
        $data = $this->apiGitHubRepository->getSymfonyRepos($page);

        if (empty($data)) {
            return;
        }

        $this->insertRepos($data);

        $page++;
        $this->importSymfonyRepos($page);
    }

    /**
     * @param int $page
     * @throws \Exception
     */
    private function importLaravelRepos($page = 1): void
    {
        $data = $this->apiGitHubRepository->getLaravelRepos($page);

        if (empty($data)) {
            return;
        }

        $this->insertRepos($data);

        $page++;
        $this->importLaravelRepos($page);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function insertRepos(array $data): void
    {
        foreach ($data as $repo) {
            if ($this->exists($repo['id'])) {
                continue;
            }

            /** @var Owner $owner */
            $owner = $this->getOrInsertOwner($repo['owner']);

            $repo = new GitHubRepository(
                $repo['id'],
                $owner,
                $repo['full_name'],
                $repo['watchers'],
                $repo['forks'],
                $repo['stargazers_count'],
                $repo['html_url']
            );

            $this->repository->save($repo);
        }
    }

    private function exists(int $id)
    {
        $repo = $this->repository->fromId($id);
        return $repo !== null;
    }
}
