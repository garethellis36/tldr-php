<?php

namespace GarethEllis\Tldr\Console\Command;

use GarethEllis\Tldr\Fetcher\CacheFetcher;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GarethEllis\Tldr\Fetcher\RemoteFetcher;
use GuzzleHttp\Client as Http;
use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Cache\StashReader;

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

        $cacheReader = new StashReader();
        $fetcher = new CacheFetcher($fetcher, $cacheReader);

        try {
            $output->write($fetcher->fetchPage($input->getArgument("page")));
        } catch (PageNotFoundException $e) {
            $output->write("Page not found");
        } catch (ClientException $e) {
            $output->write("Error connecting to GitHub");
        } catch (RequestException $e) {
            $output->write("Error sending request to GitHub");
        }
    }
}
