<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 30/01/2016
 * Time: 20:11
 */

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Page\TldrPage;

class StashReader implements CacheReaderInterface
{
    /**
     * CacheReader constructor.
     */
    public function __construct()
    {
    }

    public function readFromCache(String $pageName): TldrPage
    {
        // TODO: Implement readFromCache() method.
    }
}
