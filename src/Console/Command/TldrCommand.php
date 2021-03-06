<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Console\Command;

use GarethEllis\Tldr\Cache\StashAdapter;
use GarethEllis\Tldr\Console\Output\PageOutput;
use GarethEllis\Tldr\Fetcher\CacheFetcher;
use GarethEllis\Tldr\Fetcher\Exception\RemoteFetcherException;
use GarethEllis\Tldr\Fetcher\OperatingSystemTrait;
use Stash\Driver\FileSystem;
use Stash\Pool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GarethEllis\Tldr\Fetcher\RemoteFetcher;
use GuzzleHttp\Client as Http;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;

class TldrCommand extends Command
{
    use OperatingSystemTrait;

    protected function configure()
    {
        $this
            ->setName('tldr')
            ->setDescription('Perform a look-up against the TLDR man pages project')
            ->addArgument(
                'page',
                InputArgument::OPTIONAL,
                'The TLDR man page to look-up'
            )
            ->addOption(
                'refresh-cache',
                'r',
                InputOption::VALUE_NONE,
                "Fetch command from remote repository and refresh cache",
                null
            )
            ->addOption(
                'os',
                'o',
                InputOption::VALUE_OPTIONAL,
                "Operating system to search for: linux, osx or sunos",
                null
            )
            ->addOption(
                'flush-cache',
                'f',
                InputOption::VALUE_NONE,
                "Delete all cached pages",
                null
            )
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $http = new Http();

        $options = [];
        if ($input->getOption('os')) {
            $options["operatingSystem"] = $input->getOption('os');
        }
        $fetcher = new RemoteFetcher($http, $options);

        $cache = $this->getCacheAdapterInstance();
        $fetcher = new CacheFetcher($fetcher, $cache, $options);

        if ($input->getOption('flush-cache')) {
            $cache->flushCache();
        }

        if (!$input->getArgument("page")) {
            return $this->outputHelp($input, $output);
        }

        if ($input->getOption('refresh-cache')) {
            $operatingSystem = $input->getOption('os') ?: $this->getOperatingSystem();
            $cache->deleteFromCache($operatingSystem, $input->getArgument("page"));
        }
        try {

            $page = $fetcher->fetchPage($input->getArgument("page"));
            $pageOutput = new PageOutput($output);
            $pageOutput->write($page);

        } catch (PageNotFoundException $e) {

            return $output->writeln("<comment>Page not found</comment>");
        } catch (RemoteFetcherException $e) {

            return $output->writeln("<error>Unable to connect to repository :-(</error>");
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    protected function outputHelp(InputInterface $input, OutputInterface $output)
    {
        $help = new HelpCommand();
        $help->setCommand($this);
        return $help->run($input, $output);
    }

    /**
     * @return StashAdapter
     */
    protected function getCacheAdapterInstance()
    {
        $driver = new FileSystem([
            "path" => sys_get_temp_dir() . "tldr-cache"
        ]);
        $pool = new Pool($driver);
        $cache = new StashAdapter($pool);
        return $cache;
    }
}
