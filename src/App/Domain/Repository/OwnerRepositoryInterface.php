<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\GitHubRepository;
use App\Domain\Entity\Owner;

interface OwnerRepositoryInterface
{
    /**
     * @param int $id
     * @return Owner|null
     */
    public function fromId(int $id): ?Owner;

    /**
     * @param Owner $owner
     */
    public function save(Owner $owner): void;
}
