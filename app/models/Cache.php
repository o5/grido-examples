<?php

/**
 * Simple DibiFluent cache wrapper.
 *
 * @package     Grido
 * @author      Petr Bugyík
 */
class Cache extends \Grido\DataSources\DibiFluent
{
    const CACHE_NAMESPACE = 'Grido.Cache';

    /** @var array */
    private $dependencies = array();

    /**
     * @param \DibiFluent $fluent
     * @param array $dependencies
     */
    public function __construct(\DibiFluent $fluent, array $dependencies = array())
    {
        parent::__construct($fluent);

        $this->dependencies = $dependencies;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!$data = $this->loadFromCache()) {
            $data = parent::getData();
            $this->saveToCache($data);
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        if (!$count = $this->loadFromCache()) {
            $count = parent::getCount();
            $this->saveToCache($count);
        }

        return $count;
    }

    /**********************************************************************************************/

    /**
     * Loads data from cache.
     * @return mixed
     */
    private function loadFromCache()
    {
        return Nette\Environment::getCache(self::CACHE_NAMESPACE)->load($this->getCacheKey());
    }

    /**
     * Saves data to cache.
     * @param mixed $data
     * @param array $dependencies
     */
    private function saveToCache($data)
    {
        Nette\Environment::getCache(self::CACHE_NAMESPACE)->save(
            $this->getCacheKey(),
            $data,
            $this->dependencies
        );
    }

    /**
     * Returns cache key.
     * @return string
     */
    private function getCacheKey()
    {
        $dbt = debug_backtrace();
        return $dbt[2]['function'] . md5($this->fluent) . $this->limit . $this->offset;
    }
}
