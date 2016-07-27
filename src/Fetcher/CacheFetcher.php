<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Cache\CacheAdapterInterface;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Page\TldrPage;

class CacheFetcher implements PageFetcherInterface
{
    use OperatingSystemTrait;

    /**
     * @var PageFetcherInterface
     */
    private $fetcher;

    /**
     * @var CacheAdapterInterface
     */
    private $cache;

    public function __construct(PageFetcherInterface $fetcher, CacheAdapterInterface $cache)
    {
        $this->fetcher = $fetcher;
        $this->cache = $cache;
    }

    public function fetchPage(string $pageName, array $options = []): TldrPage
    {

        try {

            return $this->cache->readFromCache($this->getOperatingSystem($options), $pageName);
        } catch (PageNotFoundException $e) {

            $page = $this->fetcher->fetchPage($pageName);
            $this->cache->writeToCache($page);

            return $page;
        }
    }
}
