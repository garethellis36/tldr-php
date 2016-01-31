<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Page\TldrPage;

interface PageFetcherInterface
{
    public function fetchPage(string $pageName): TldrPage;
}
