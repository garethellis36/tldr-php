<?php

namespace GarethEllis\Tldr\Console\Output;

use GarethEllis\Tldr\Page\TldrPage;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class PageOutput
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function write(TldrPage $page)
    {
        $this->output->writeln("");

        $lines = explode(PHP_EOL, $page->getContent());

        foreach ($lines as $line) {
            $this->writeLine($line);
        }

        $this->output->writeln("");
    }

    protected function writeLine($line)
    {
        if (empty(trim($line))) {
            return false;
        }

        if ($this->isHeading($line)) {
            return $this->outputHeading($line);
        }

        if ($this->isDescription($line)) {
            return $this->outputDescription($line);
        }

        if ($this->isHowToIntro($line)) {
            return $this->outputHowToIntro($line);
        }

        return $this->outputHowToCommand($line);
    }

    protected function isHeading($line)
    {
        return substr($line, 0, 2) === "# ";
    }

    protected function isDescription($line)
    {
        return substr($line, 0, 2) === "> ";
    }

    protected function isHowToIntro($line)
    {
        return substr($line, 0, 2) === "- ";
    }

    protected function isHowToCommand($line)
    {
        return substr($line, 0, 1) === "`" && substr($line, strlen($line) - 1, 1) === "`";
    }

    protected function outputHeading($line)
    {
        $this->setHeadingStyle();
        $this->output->writeln($this->getOutputString($line, "heading"));
    }

    protected function outputDescription($line)
    {
        $this->setDescriptionStyle();
        $this->output->writeln($this->getOutputString($line, "description", true));
    }

    protected function outputHowToIntro($line)
    {
        $this->setHowToIntroStyle();
        $this->output->writeln($this->getOutputString($line, "howtointro"));
    }

    protected function outputHowToCommand($line)
    {
        $this->setHowToCommandStyle();
        $this->output->writeln($this->getOutputString($line, "howtocommand", true));
    }

    protected function stripMarkdown($line)
    {
        if ($this->isHeading($line)) {
            return substr_replace($line, "", 0, 2);
        }

        if ($this->isDescription($line)) {
            return substr_replace($line, "", 0, 2);
        }

        if ($this->isHowToIntro($line)) {
            return $line;
        }

        $line = substr_replace($line, "", 0, 1);
        $line = substr_replace($line, "", strlen($line) -1, 1);
        $line = str_replace("{{", "<", $line);
        return str_replace("}}", ">", $line);
    }

    protected function setHeadingStyle()
    {
        $style = new OutputFormatterStyle('green', 'black', array('bold'));
        $this->output->getFormatter()->setStyle('heading', $style);
    }

    protected function setDescriptionStyle()
    {
        $style = new OutputFormatterStyle('white', 'black', array('bold'));
        $this->output->getFormatter()->setStyle('description', $style);
    }

    protected function setHowToIntroStyle()
    {
        $style = new OutputFormatterStyle('green', 'black', array('bold'));
        $this->output->getFormatter()->setStyle('howtointro', $style);
    }

    protected function setHowToCommandStyle()
    {
        $style = new OutputFormatterStyle('red', 'black', array('bold'));
        $this->output->getFormatter()->setStyle('howtocommand', $style);
    }

    protected function getOutputString(String $line, String $tag, Bool $newLineAfter = false): String
    {
        $indent = str_repeat(" ", $this->isHowToCommand($line) ? 4 : 2);
        return $indent . "<{$tag}>" . $this->stripMarkdown($line). "</{$tag}>"
            . ($newLineAfter ? PHP_EOL : null);
    }
}
