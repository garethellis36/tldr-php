<?php

namespace GarethEllis\Tldr;

use GarethEllis\Tldr\Console\Command\GarethEllis\Tldr\Exception\PageNotFoundException;

class Client
{
    protected $http;

    /**
     * @var string The base URL of the JSON index containing a list of commands
     */
    protected $baseUrl = "https://api.github.com/repos/tldr-pages/tldr/contents/pages/index.json?ref=master";

    protected $pageInfoUrlTemplate = "https://api.github.com/repos/tldr-pages/tldr/contents/pages/{os}/{command}.md?ref=master";

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
        $this->baseUrl = $url;
    }

    public function getPagesUrl()
    {
        return $this->baseUrl;
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

        //working on assumption that only one page has been found!
        if (empty($pages)) {
            throw new PageNotFoundException;
        }

        $page = $pages["0"];
        $url = str_replace("{os}", $page["platform"][0], $this->pageInfoUrlTemplate);
        $url = str_replace("{command}", $page["name"], $url);

        $response = $this->http->get($url);
        $contents = json_decode($response->getBody()->getContents(), true);

        $pageContent = base64_decode($contents["content"]);

        return $pageContent;
    }
}
