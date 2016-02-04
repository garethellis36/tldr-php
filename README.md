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

`bin/tldr <command>`

### Options ###

`tldr <command> --os=osx`
Filter for man pages relating to the given operating system. Without this option set, the application will attempt to determine your OS and use that. Valid operating system values are `osx`, `linux` and `sunos`.

`tldr <command> --refresh-cache`
Forces the application to reload the given man page from the remote source and refreshes the local cache.

`tldr --flush-cache`
Delete all man pages from the local cache. **Currently not working!**

## More ##

[Contributing](CONTRIBUTING.md)   
[License](LICENSE.md)