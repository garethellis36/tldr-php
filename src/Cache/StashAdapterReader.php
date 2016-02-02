<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Page\TldrPage;

class StashAdapter implements CacheAdapterInterface
{
    /**
     * CacheReader constructor.
     */
    public function __construct()
    {
    }

    public function getPageList(): array
    {
        // TODO: Implement getPageList() method.
    }

    public function readFromCache(String $platform, String $pageName): TldrPage
    {
        // TODO: Implement readFromCache() method.
    }

    public function writeToCache(TldrPage $page)
    {
        // TODO: Implement writeToCache() method.
    }
}
