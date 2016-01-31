<?php

namespace GarethEllis\Tldr\Tests\Page;

use GarethEllis\Tldr\Page\TldrPage;

class TldrPageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructionSetsNameAndContentProperties()
    {
        $name = "Test";
        $platform = "common";
        $content = "Lorem ipsum";
        $page = new TldrPage($name, $platform, $content);

        $this->assertEquals($name, $page->getName(), "Failed asserting that page name is '{$name}''");
        $this->assertEquals($platform, $page->getPlatform(), "Failed asserting that OS is {$platform}");
        $this->assertEquals($content, $page->getContent(), "Failed asserting that content is '{$content}'");
    }

}