<?php

namespace App\Domain\Entity;

/**
 * Class Owner
 * @package App\Domain\Entity
 */
class Owner
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /**
     * Owner constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
