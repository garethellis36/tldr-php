<?php

namespace GarethEllis\Tldr\Test\Page;

use GarethEllis\Tldr\Page\TldrPage;

class TldrPageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructionSetsNameAndContentProperties()
    {
        $name = "Test";
        $os = "common";
        $content = "Lorem ipsum";
        $page = new TldrPage($name, $os, $content);

        $this->assertEquals($name, $page->getName(), "Failed asserting that page name is '{$name}''");
        $this->assertEquals($os, $page->getOs(), "Failed asserting that OS is {$os}");
        $this->assertEquals($content, $page->getContent(), "Failed asserting that content is '{$content}'");
    }

}