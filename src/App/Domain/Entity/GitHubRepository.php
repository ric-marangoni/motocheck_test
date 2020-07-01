<?php

namespace App\Domain\Entity;

/**
 * Class GitHubRepository
 * @package App\Domain\Entity
 */
class GitHubRepository
{
    /** @var int */
    private $id;

    /** @var Owner */
    private $owner;

    /** @var string */
    private $name;

    /** @var int */
    private $watchers;

    /** @var int */
    private $forks;

    /** @var int */
    private $stars;

    /** @var string */
    private $url;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * GitHubRepository constructor.
     * @param int $id
     * @param Owner $owner
     * @param string $name
     * @param int $watchers
     * @param int $forks
     * @param int $stars
     * @param string $url
     * @throws \Exception
     */
    public function __construct(int $id, Owner $owner, string $name, int $watchers, int $forks, int $stars, string $url)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->name = $name;
        $this->watchers = $watchers;
        $this->forks = $forks;
        $this->stars = $stars;
        $this->url = $url;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWatchers(): int
    {
        return $this->watchers;
    }

    /**
     * @return int
     */
    public function getForks(): int
    {
        return $this->forks;
    }

    /**
     * @return int
     */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
