<?php

namespace GarethEllis\Tldr\Console;

use GarethEllis\Tldr\Console\Command\TldrCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TldrApplication extends Application
{
    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        // This should return the name of your command.
        return 'tldr';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new TldrCommand();
        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * Override parent method so that --help options is used when app is called with no arguments or options
     *
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int
     * @throws \Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if ($input === null) {
            if (count($_SERVER["argv"]) <= 1) {
                $args = array_merge($_SERVER["argv"], ["--help"]);
                $input = new ArgvInput($args);
            }
        }
        return parent::run($input, $output);
    }
}
