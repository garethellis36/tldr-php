<?php

namespace GarethEllis\Tldr\Console\Command;

use GarethEllis\Tldr\Console\Output\PageOutput;
use GarethEllis\Tldr\Fetcher\Exception\RemoteFetcherException;
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

        //$cacheReader = new StashReader();
        //$fetcher = new CacheFetcher($fetcher, $cacheReader);

        try {

            $page = $fetcher->fetchPage($input->getArgument("page"));
            $output = new PageOutput($output);
            $output->write($page);

        } catch (PageNotFoundException $e) {

            return $output->write("Page not found :-(");
        } catch (RemoteFetcherException $e) {

            return $output->write("<error>Unable to connect to repository :-(</error>");
        }
    }
}
