<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Page\TldrPage;

interface CacheAdapterInterface
{
    /**
     * Retrieve a specific page from cache
     *
     * @param String $platform
     * @param String $pageName
     * @throw CacheRecordNotFoundException
     * @return TldrPage
     */
    public function readFromCache(String $platform, String $pageName): TldrPage;

    /**
     * Store a page in cache
     *
     * @param TldrPage $page
     * @return void
     */
    public function writeToCache(TldrPage $page);
}
