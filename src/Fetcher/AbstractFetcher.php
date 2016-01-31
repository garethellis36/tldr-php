<?php

namespace GarethEllis\Tldr\Fetcher;

class AbstractFetcher
{
    protected function findPageInList(String $page, array $list)
    {
        return array_filter($list, function ($foundPage) use ($page) {
            return $foundPage["name"] === $page;
        });
    }
}
