<?php

namespace GarethEllis\Tldr\Console\Command;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GarethEllis\Tldr\Client;
use GuzzleHttp\Client as Http;

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
        $client = new Client($http);

        try {
            $output->write($client->lookupPage($input->getArgument("page")));
        } catch (GarethEllis\Tldr\Exception\PageNotFoundException $e) {
            $output->write("Page not found");
        } catch (ClientException $e) {
            $output->write("Error connecting to GitHub");
        } catch (RequestException $e) {
            $output->write("Error sending request to GitHub");
        }
    }
}
