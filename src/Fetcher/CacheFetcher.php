<?php

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Cache\CacheReaderInterface;
use GarethEllis\Tldr\Fetcher\Exception\CachedPageNotFoundException;
use GarethEllis\Tldr\Page\TldrPage;

class CacheFetcher extends AbstractFetcher implements PageFetcherInterface
{
    /**
     * @var PageFetcherInterface
     */
    private $fetcher;

    /**
     * @var CacheReaderInterface
     */
    private $cacheReader;

    public function __construct(PageFetcherInterface $fetcher, CacheReaderInterface $cacheReader)
    {
        $this->fetcher = $fetcher;
        $this->cacheReader = $cacheReader;
    }

    public function fetchPage(string $pageName): TldrPage
    {
        $pages = $this->findPageInList($pageName, $this->cacheReader->getPageList());

        if (empty($pages)) {
            return $this->fetcher->fetchPage($pageName);
        }

        $page = $pages["0"];
        $platform = $page["platform"][0];

        try {

            return $this->cacheReader->readFromCache($platform, $pageName);
        } catch (CachedPageNotFoundException $e) {

            return $this->fetcher->fetchPage($pageName);
        }
    }
}
