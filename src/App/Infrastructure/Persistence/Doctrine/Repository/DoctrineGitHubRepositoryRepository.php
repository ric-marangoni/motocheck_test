<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Infrastructure\Container\Application\Utils\Doctrine\AbstractDoctrineRepository;
use App\Domain\Entity\GitHubRepository;
use App\Domain\Repository\GitHubRepositoryInterface;

final class DoctrineGitHubRepositoryRepository extends AbstractDoctrineRepository implements GitHubRepositoryInterface
{
    protected function getAlias()
    {
        return 'gtr';
    }

    /**
     * @param int $id
     * @return GitHubRepository|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fromId(int $id): ?GitHubRepository
    {
        $qb = $this->createQueryBuilder($this->getAlias());

        $qb->where('gtr.id = :id');
        $qb->setParameter('id', (string)$id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getRepositories(int $offset, int $limit, bool $exportCsv): array
    {
        if ($exportCsv) {
            $limit = 10;
        }

        $qb = $this->createQueryBuilder($this->getAlias());
        $qb->select(
            'o.name as owner,
            gtr.name as project,
            gtr.stars,
            gtr.forks,
            gtr.watchers,
            gtr.url'
        );
        $qb->innerJoin('gtr.owner', 'o');

        if ($exportCsv) {
            $qb->where('gtr.watchers >= 100');
            $qb->andWhere('gtr.stars > 2000');
        }

        $qb->addOrderBy('gtr.stars', 'DESC');

        $rows = $qb
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();

        $total = $qb->select('count(DISTINCT gtr.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return ['rows' => $rows, 'totalRows' => $total, 'limit' => $limit];
    }

    /**
     * @param GitHubRepository $attendant
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(GitHubRepository $attendant): void
    {
        $this->getEntityManager()->persist($attendant);
        $this->getEntityManager()->flush();
    }
}
