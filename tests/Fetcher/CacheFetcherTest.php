<?php

namespace GarethEllis\Tldr\Tests\Fetcher;

use GarethEllis\Tldr\Fetcher\CacheFetcher;
use GarethEllis\Tldr\Cache\CacheAdapterInterface;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Fetcher\PageFetcherInterface;
use GarethEllis\Tldr\Page\TldrPage;
use GarethEllis\Tldr\Fetcher\Exception\CachedPageNotFoundException;

class CacheFetcherTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReturnAPageFromCache()
    {
        /**
         * @var $fetcherInterface PageFetcherInterface
         * @var $cacheInterface CacheAdapterInterface
         */
        $fetcherInterface = $this->getMock(PageFetcherInterface::class);
        $cacheInterface = $this->getMock(CacheAdapterInterface::class);

        $mockPage = $this->getMock(TldrPage::class, [], ["7za", "common", "blabla"]);
        $cacheInterface->expects($this->at(0))
            ->method("readFromCache")
            ->will($this->returnValue($mockPage));

        $fetcher = new CacheFetcher($fetcherInterface, $cacheInterface);
        $this->assertEquals($mockPage, $fetcher->fetchPage("7za"));
    }

    public function testCanReturnAPageFromNextDecoratorIfPageNotFoundInCache()
    {
        /**
         * @var $fetcherInterface PageFetcherInterface
         * @var $cacheInterface CacheAdapterInterface
         */
        $fetcherInterface = $this->getMock(PageFetcherInterface::class);
        $cacheInterface = $this->getMock(CacheAdapterInterface::class);

        $cacheInterface->expects($this->at(0))
            ->method("readFromCache")
            ->will($this->throwException(new PageNotFoundException()));

        $mockPage = $this->getMock(TldrPage::class, [], ["not-a-page-in-cached-list", "common", "blabla"]);
        $fetcherInterface->expects($this->once())
            ->method("fetchPage")
            ->with("not-a-page-in-cache")
            ->will($this->returnValue($mockPage));

        $cacheInterface->expects($this->at(1))
            ->method("writeToCache");

        $fetcher = new CacheFetcher($fetcherInterface, $cacheInterface);
        $this->assertEquals($mockPage, $fetcher->fetchPage("not-a-page-in-cache"));
    }
}