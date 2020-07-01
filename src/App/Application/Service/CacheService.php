<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Exception\CacheServiceException;
use Predis\ClientInterface;

final class CacheService
{
    private const ONE_MINUTE_IN_SECONDS = 60;
    private const ONE_HOUR_IN_SECONDS   = 3600;
    private const ONE_DAY_IN_SECONDS    = 86400;

    /** @var ClientInterface */
    private $redis;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $key
     * @param bool $isDecode
     * @return mixed|string|null
     */
    public function get(string $key, bool $isDecode = true)
    {
        $dataCache = $this->redis->get($key);
        if (null === $dataCache) {
            return null;
        }

        if (!$isDecode) {
            return $dataCache;
        }

        return json_decode($dataCache, true);
    }

    /**
     * Get the values of all the given keys
     * @param array $keys
     * @return array
     * @throws CacheServiceException
     */
    public function getMultiple(array $keys):? array
    {
        if (empty($keys)) {
            throw new CacheServiceException('keys are empty. Not allowed.');
        }

        $dataCache = $this->redis->mget($keys);
        if (empty($dataCache)) {
            return null;
        }

        return $dataCache;
    }

    /**
     * Find all keys matching the given pattern then get the values of all the keys
     * @param string $match
     * @param bool $left
     * @param bool $right
     * @return array
     * @throws CacheServiceException
     */
    public function getMatch(string $match, bool $left = true, bool $right = true):? array
    {
        $pattern = sprintf('%s%s%s', ($left ? '*' : ''), $match, ($right ? '*' : ''));

        $keys = $this->redis->keys($pattern);
        return $this->getMultiple($keys);
    }

    /**
     * Set the string value of a key
     * @param string $key
     * @param int $seconds
     * @param null $data
     * @throws CacheServiceException
     */
    public function set(string $key, int $seconds, $data = null): void
    {
        if (null === $data) {
            throw new CacheServiceException('Data is null. Not allowed');
        }

        if ($this->isJson($data) === false) {
            $data = \GuzzleHttp\json_encode($data);
        }

        $this->redis->setex($key, $seconds, $data);
    }

    /**
     * Set the string value of a key (expires in minutes)
     * @param string $key
     * @param int $minutes
     * @param $data
     * @throws CacheServiceException
     */
    public function setInMinutes(string $key, int $minutes, $data = null): void
    {
        $this->set($key, self::ONE_MINUTE_IN_SECONDS * $minutes, $data);
    }

    /**
     * Set the string value of a key (expires in hours)
     * @param string $key
     * @param int $hours
     * @param null $data
     * @throws CacheServiceException
     */
    public function setInHours(string $key, int $hours, $data = null): void
    {
        $this->set($key, self::ONE_HOUR_IN_SECONDS * $hours, $data);
    }

    /**
     * Set the string value of a key (expires in days)
     * @param string $key
     * @param int $days
     * @param null $data
     * @throws CacheServiceException
     */
    public function setInDays(string $key, int $days, $data = null): void
    {
        $this->set($key, self::ONE_DAY_IN_SECONDS * $days, $data);
    }

    /**
     * Set multiple keys to multiple values
     * @param array $keysValues
     * @throws CacheServiceException
     */
    public function setMultiple(array $keysValues): void
    {
        if (empty($keysValues)) {
            throw new CacheServiceException('keysValues are empty. Not allowed.');
        }

        $this->redis->mset($keysValues);
    }

    /**
     * Determine if a key exists
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return (bool)$this->redis->exists($key);
    }

    /**
     * Delete a key
     * @param $key
     */
    public function remove($key): void
    {
        if (is_array($key) === false) {
            $key = [$key];
        }

        $this->redis->del($key);
    }

    /**
     * Delete a key
     * @param $key
     */
    public function delete($key): void
    {
        $this->remove($key);
    }

    /**
     * Check if a string is JSON
     * @param $data
     * @return bool
     */
    public function isJson($data): bool
    {
        if (is_array($data)) {
            return false;
        }

        json_decode((string)$data);
        return json_last_error() === JSON_ERROR_NONE;
    }
}