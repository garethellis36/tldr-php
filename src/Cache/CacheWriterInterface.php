<?php

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Page\TldrPage;

interface CacheWriterInterface
{
    public function writeToCache(TldrPage $page);
}
