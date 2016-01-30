<?php

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Cache\CacheReaderInterface;
use GarethEllis\Tldr\Page\TldrPage;

class CacheFetcher implements PageFetcherInterface
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

    }
}
