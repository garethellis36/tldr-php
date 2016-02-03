<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Console\Command;

use GarethEllis\Tldr\Cache\StashAdapter;
use GarethEllis\Tldr\Console\Output\PageOutput;
use GarethEllis\Tldr\Fetcher\CacheFetcher;
use GarethEllis\Tldr\Fetcher\Exception\RemoteFetcherException;
use Stash\Driver\FileSystem;
use Stash\Pool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GarethEllis\Tldr\Fetcher\RemoteFetcher;
use GuzzleHttp\Client as Http;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;

class TldrCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('tldr')
            ->setDescription('Perform a look-up against the TLDR man pages project')
            ->addArgument(
                'page',
                InputArgument::REQUIRED,
                'Please specify a man page to look-up.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $http = new Http();
        $fetcher = new RemoteFetcher($http);

        $driver = new FileSystem([
            "path" => sys_get_temp_dir()
        ]);
        $pool = new Pool($driver);
        $cache = new StashAdapter($pool);
        $fetcher = new CacheFetcher($fetcher, $cache);

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
}
