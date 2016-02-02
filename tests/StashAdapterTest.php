<?php

namespace GarethEllis\Tldr\Tests\Cache;


use GarethEllis\Tldr\Cache\StashAdapter;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Page\TldrPage;
use Stash\Item;
use Stash\Pool;

class StashAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testReadFromCache()
    {
        $page = $this->getMock(
            TldrPage::class,
            [],
            ["7za", "common", "blabla"]
        );

        $item = $this->getMock(Item::class);
        $item->expects($this->once())
            ->method("get")
            ->will($this->returnValue($page));

        $item->expects($this->once())
            ->method("isMiss")
            ->will($this->returnValue(false));

        $pool = $this->getMock(Pool::class);
        $pool->expects($this->once())
            ->method("getItem")
            ->with("common/7za")
            ->will($this->returnValue($item));

        $stash = new StashAdapter($pool);
        $stash->readFromCache("common", "7za");
    }

    public function testWriteToCache()
    {
        $page = $this->getMock(
            TldrPage::class,
            [],
            ["7za", "common", "blabla"]
        );
        $page->expects($this->once())
            ->method("getPlatform")
            ->will($this->returnValue("common"));
        $page->expects($this->once())
            ->method("getName")
            ->will($this->returnValue("7za"));

        $item = $this->getMock(Item::class);
        $item->expects($this->once())
            ->method("lock");

        $item->expects($this->once())
            ->method("set")
            ->with($page);

        $pool = $this->getMock(Pool::class);
        $pool->expects($this->once())
            ->method("getItem")
            ->with("common/7za")
            ->will($this->returnValue($item));

        $stash = new StashAdapter($pool);
        $stash->writeToCache($page);
    }

    public function testReadFromCacheHandleMiss()
    {
        $item = $this->getMock(Item::class);
        $item->expects($this->once())
            ->method("get")
            ->will($this->returnValue(null));

        $item->expects($this->once())
            ->method("isMiss")
            ->will($this->returnValue(true));

        $pool = $this->getMock(Pool::class);
        $pool->expects($this->once())
            ->method("getItem")
            ->with("common/7za")
            ->will($this->returnValue($item));

        $this->setExpectedException(PageNotFoundException::class);

        $stash = new StashAdapter($pool);
        $stash->readFromCache("common", "7za");
    }
}