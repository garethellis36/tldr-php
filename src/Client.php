<?php
namespace GarethEllis\Tldr;


class Client
{
    protected $http;

    protected $pagesUrl = "https://api.github.com/repos/tldr-pages/tldr/contents/pages/index.json?ref=master";

    /**
     * Client constructor.
     * @param \GuzzleHttp\Client $http
     */
    public function __construct($http)
    {
        $this->http = $http;
    }

    public function setPagesUrl(string $url)
    {
        $this->pagesUrl = $url;
    }

    public function getPagesUrl()
    {
        return $this->pagesUrl;
    }

    public function lookupPage(string $page)
    {
        $response = $this->http->get($this->getPagesUrl());
        $contents = json_decode($response->getBody()->getContents(), true);
        $pages = base64_decode($contents["content"]);
        $json = json_decode($pages, true);
        $pages = $json["commands"];
        $pages = array_filter($pages, function ($foundPage) use ($page) {
            return $foundPage["name"] === $page;
        });

        foreach ($pages as $page) {

        }
    }
}