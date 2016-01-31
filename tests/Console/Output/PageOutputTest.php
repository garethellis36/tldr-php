<?php

namespace GarethEllis\Tldr\Test\Console\Output;

use GarethEllis\Tldr\Console\Output\PageOutput;
use GarethEllis\Tldr\Page\TldrPage;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

class PageOutputTest extends \PHPUnit_Framework_TestCase
{
    public function testCanConvertMarkdownAndOutput()
    {
        /**
         * @var $output OutputInterface
         */
        $output = $this->getMock(OutputInterface::class);
        $mockFormatter = $this->GetMock(OutputFormatterInterface::class);

        $output->expects($this->at(0))
            ->method('writeln')
            ->with("");

        $output->expects($this->at(1))
            ->method("getFormatter")
            ->will($this->returnValue($mockFormatter));

        $output->expects($this->at(2))
            ->method("writeln")
            ->with("  <heading>7za</heading>");

        $output->expects($this->at(3))
            ->method("getFormatter")
            ->will($this->returnValue($mockFormatter));

        $output->expects($this->at(4))
            ->method("writeln")
            ->with("  <description>A file archiver with high compression ratio.</description>" . PHP_EOL);

        $output->expects($this->at(5))
            ->method("getFormatter")
            ->will($this->returnValue($mockFormatter));

        $output->expects($this->at(6))
            ->method("writeln")
            ->with("  <howtointro>- Compress directory or file:</howtointro>");

        $output->expects($this->at(7))
            ->method("getFormatter")
            ->will($this->returnValue($mockFormatter));

        $output->expects($this->at(8))
            ->method("writeln")
            ->with("    <howtocommand>7za a <compressed.7z> <directory_or_file_to_compress></howtocommand>" . PHP_EOL);

        $output->expects($this->at(9))
            ->method('writeln')
            ->with("");

        $mockFormatter->expects($this->at(0))
            ->method("setStyle")
            ->with("heading", $this->isInstanceOf(OutputFormatterStyle::class));


        $content = "# 7za" . PHP_EOL
                . "" . PHP_EOL
                . "> A file archiver with high compression ratio." . PHP_EOL
                . "" . PHP_EOL
                . "- Compress directory or file:" . PHP_EOL
                . "" . PHP_EOL
                . "`7za a {{compressed.7z}} {{directory_or_file_to_compress}}`";
        $pageOutput = new PageOutput($output);

        /**
         * @var $page TldrPage
         */
        $page = $this->getMock(TldrPage::class, [], ["7za", "common", $content]);
        $page->expects($this->once())
            ->method("getContent")
            ->will($this->returnValue($content));

        $pageOutput->write($page);
    }
}