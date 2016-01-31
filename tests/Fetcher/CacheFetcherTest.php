<?php

namespace GarethEllis\Tldr\Tests\Fetcher;

use GarethEllis\Tldr\Fetcher\CacheFetcher;
use GarethEllis\Tldr\Cache\CacheReaderInterface;
use GarethEllis\Tldr\Fetcher\PageFetcherInterface;
use GarethEllis\Tldr\Page\TldrPage;
use GarethEllis\Tldr\Fetcher\Exception\CachedPageNotFoundException;

class CacheFetcherTest extends \PHPUnit_Framework_TestCase
{
    public function testCanReturnAPageFromCache()
    {
        /**
         * @var $fetcherInterface PageFetcherInterface
         * @var $cacheReaderInterface CacheReaderInterface
         */
        $fetcherInterface = $this->getMock(PageFetcherInterface::class);
        $cacheReaderInterface = $this->getMock(CacheReaderInterface::class);

        $sampleApiResponse = file_get_contents(__DIR__ . "/../SampleApiResponses/PageList.json");
        $array = json_decode($sampleApiResponse, true);
        $pageList = json_decode(base64_decode($array["content"]), true);
        $pageList = $pageList["commands"];

        $cacheReaderInterface->expects($this->at(0))
            ->method("getPageList")
            ->will($this->returnValue($pageList));

        $mockPage = $this->getMock(TldrPage::class, [], ["7za", "common", "blabla"]);
        $cacheReaderInterface->expects($this->at(1))
            ->method("readFromCache")
            ->will($this->returnValue($mockPage));

        $fetcher = new CacheFetcher($fetcherInterface, $cacheReaderInterface);
        $this->assertEquals($mockPage, $fetcher->fetchPage("7za"));
    }

    public function testCanReturnAPageFromNextDecoratorIfPageNotFoundInCachedList()
    {
        /**
         * @var $fetcherInterface PageFetcherInterface
         * @var $cacheReaderInterface CacheReaderInterface
         */
        $fetcherInterface = $this->getMock(PageFetcherInterface::class);
        $cacheReaderInterface = $this->getMock(CacheReaderInterface::class);

        $sampleApiResponse = file_get_contents(__DIR__ . "/../SampleApiResponses/PageList.json");
        $array = json_decode($sampleApiResponse, true);
        $pageList = json_decode(base64_decode($array["content"]), true);
        $pageList = $pageList["commands"];

        $cacheReaderInterface->expects($this->at(0))
            ->method("getPageList")
            ->will($this->returnValue($pageList));

        $mockPage = $this->getMock(TldrPage::class, [], ["not-a-page-in-cached-list", "common", "blabla"]);
        $fetcherInterface->expects($this->once())
            ->method("fetchPage")
            ->with("not-a-page-in-cached-list")
            ->will($this->returnValue($mockPage));

        $fetcher = new CacheFetcher($fetcherInterface, $cacheReaderInterface);
        $this->assertEquals($mockPage, $fetcher->fetchPage("not-a-page-in-cached-list"));
    }

    public function testCanReturnAPageFromNextDecoratorIfPageFoundInCachedListButActualPageNotCached()
    {
        /**
         * @var $fetcherInterface PageFetcherInterface
         * @var $cacheReaderInterface CacheReaderInterface
         */
        $fetcherInterface = $this->getMock(PageFetcherInterface::class);
        $cacheReaderInterface = $this->getMock(CacheReaderInterface::class);

        $sampleApiResponse = file_get_contents(__DIR__ . "/../SampleApiResponses/PageList.json");
        $array = json_decode($sampleApiResponse, true);
        $pageList = json_decode(base64_decode($array["content"]), true);
        $pageList = $pageList["commands"];

        $cacheReaderInterface->expects($this->at(0))
            ->method("getPageList")
            ->will($this->returnValue($pageList));

        $cacheReaderInterface->expects($this->at(1))
            ->method("readFromCache")
            ->will($this->throwException(new CachedPageNotFoundException()));

        $mockPage = $this->getMock(TldrPage::class, [], ["7za", "common", "blabla"]);
        $fetcherInterface->expects($this->once())
            ->method("fetchPage")
            ->with("7za")
            ->will($this->returnValue($mockPage));

        $fetcher = new CacheFetcher($fetcherInterface, $cacheReaderInterface);
        $this->assertInstanceOf(
            TldrPage::class, $fetcher->fetchPage("7za"), "Failed asserting that decorator returned TldrPage instance")
        ;
    }
}