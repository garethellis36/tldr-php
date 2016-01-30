#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';

use GarethEllis\Tldr\Console\Command\TldrCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new TldrCommand());
$application->run();
