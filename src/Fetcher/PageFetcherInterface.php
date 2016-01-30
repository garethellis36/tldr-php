<?php

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Page\TldrPage;

interface PageFetcherInterface
{
    public function fetchPage(string $pageName): TldrPage;
}
