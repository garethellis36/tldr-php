<?php

namespace GarethEllis\Tldr\Test\Fetcher;

use GarethEllis\Tldr\Fetcher\RemoteFetcher;
use GuzzleHttp\Client;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use GarethEllis\Tldr\Page\TldrPage;
use GarethEllis\Tldr\Cache\CacheWriterInterface;

class RemoteFetcherTest extends \PHPUnit_Framework_TestCase
{
    public function testCanFetchAPageAndInstantiateANewPageObject()
    {
        /**
         * @var $http Client
         */
        $http = $this->getMock(Client::class, ["get"]);
        $mockStream = $this->getMock(StreamInterface::class);
        $mockResponse = $this->getMock(MessageInterface::class);

        /**
         * Set-up mocking first remote call (fetches list of pages)
         */
        $mockStream->expects($this->at(0))
            ->method("getContents")
            ->will($this->returnValue(file_get_contents(__DIR__ . "/../SampleApiResponses/PageList.json")));

        $mockResponse->expects($this->at(0))
            ->method("getBody")
            ->will($this->returnValue($mockStream));

        $http->expects($this->at(0))
            ->method("get")
            ->with($this->stringContains("pages/index.json"))
            ->will($this->returnValue($mockResponse));

        /**
         * Set-up mocking second remote call (fetches individual page)
         */
        $mockStream->expects($this->at(1))
            ->method("getContents")
            ->will($this->returnValue(file_get_contents(__DIR__ . "/../SampleApiResponses/Page.json")));

        $mockResponse->expects($this->at(1))
            ->method("getBody")
            ->will($this->returnValue($mockStream));

        $http->expects($this->at(1))
            ->method("get")
            ->with($this->stringContains("common/7za"))
            ->will($this->returnValue($mockResponse));

        /**
         * Set-up mocking of cache writer
         */
        $cacheWriter = $this->getMock(CacheWriterInterface::class);
        $cacheWriter->expects($this->once())
            ->method("writeToCache");
            //TODO add ->with(anInstanceOf(TldrPage::class))

        $fetcher = new RemoteFetcher($http, $cacheWriter);
        $this->assertInstanceOf(TldrPage::class, $fetcher->fetchPage("7za"));
    }

}