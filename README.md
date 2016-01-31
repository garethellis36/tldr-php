# TLDR - PHP client for TLDR man pages #

[![Build Status](https://travis-ci.org/garethellis36/tldr-php.svg?branch=master)](https://travis-ci.org/garethellis36/tldr-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/garethellis36/tldr-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/garethellis36/tldr-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/garethellis36/tldr-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/garethellis36/tldr-php/?branch=master)
[![Code Climate](https://codeclimate.com/github/garethellis36/tldr-php/badges/gpa.svg)](https://codeclimate.com/github/garethellis36/tldr-php)

This is a PHP client for the [TLDR man pages project](https://github.com/tldr-pages/tldr).

## Requirements ##

- Internet connectivity (pages will be cached for faster retrieval and offline usage)
- PHP >= 7

## Usage ##

This client is still in development. At this time, to use the client, you should clone this repo and
run the following:

`php src/application.php tldr <command>`

## More ##

[Contributing](CONTRIBUTING.md)   
[License](LICENSE.md)