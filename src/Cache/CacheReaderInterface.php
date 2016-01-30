<?php

namespace GarethEllis\Tldr\Cache;

use GarethEllis\Tldr\Page\TldrPage;

interface CacheReaderInterface
{
    public function readFromCache(String $pageName): TldrPage;
}
