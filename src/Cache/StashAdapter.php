<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Cache\Exception\InvalidCacheDriverException;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Page\TldrPage;
use Stash\Driver\Ephemeral;
use Stash\Pool;

class StashAdapter implements CacheAdapterInterface
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * CacheReader constructor.
     */
    public function __construct(Pool $pool)
    {
        if ($pool->getDriver() instanceof Ephemeral) {
            throw new InvalidCacheDriverException("Ephemeral cannot be used as a cache driver in this app");
        }
        $this->pool = $pool;
    }

    public function readFromCache(String $platform, String $pageName): TldrPage
    {
        $item = $this->pool->getItem($platform . "/" . $pageName);
        $page = $item->get();

        if ($item->isMiss()) {

            $item = $this->pool->getItem("common/" . $pageName);
            $page = $item->get();

            if ($item->isMiss()) {
                throw new PageNotFoundException();
            }
        }

        return $page;
    }

    public function writeToCache(TldrPage $page)
    {
        $item = $this->pool->getItem($page->getPlatform() . "/" . $page->getName());
        $item->lock();
        $item->set($page);
    }
}
