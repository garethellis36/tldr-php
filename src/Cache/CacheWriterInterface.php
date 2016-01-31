<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Page\TldrPage;

interface CacheWriterInterface
{
    public function writeToCache(TldrPage $page);
}
