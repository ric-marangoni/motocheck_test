<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\Owner;
use App\Domain\Repository\OwnerRepositoryInterface;
use App\Infrastructure\Container\Application\Utils\Doctrine\AbstractDoctrineRepository;
use App\Domain\Entity\GitHubRepository;

final class DoctrineOwnerRepository extends AbstractDoctrineRepository implements OwnerRepositoryInterface
{
    protected function getAlias()
    {
        return 'o';
    }

    /**
     * @param int $id
     * @return Owner|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function fromId(int $id): ?Owner
    {
        $qb = $this->createQueryBuilder($this->getAlias());

        $qb->where('o.id = :id');
        $qb->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Owner $owner
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Owner $owner): void
    {
        $this->getEntityManager()->persist($owner);
        $this->getEntityManager()->flush();
    }
}
