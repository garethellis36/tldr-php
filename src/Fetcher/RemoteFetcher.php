<?php

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Page\TldrPage;
use GuzzleHttp\Client;
use GarethEllis\Tldr\Cache\CacheWriterInterface;
use GarethEllis\Tldr\Fetcher\Exception\RemoteFetcherException;

/**
 * Class RemoteFetcher
 *
 * Fetches a page from the remote repository and optionally caches the result
 *
 * @package GarethEllis\Tldr\Fetcher
 */
class RemoteFetcher extends AbstractFetcher implements PageFetcherInterface
{

    protected $http;

    /**
     * @var string The base URL of the JSON index containing a list of commands
     */
    protected $baseUrl = "https://api.github.com/repos/tldr-pages/tldr/contents/pages/index.json?ref=master";

    /**
     * @var string URL template for fetching an individual page
     */
    protected $pageInfoUrlTemplate =
        "https://api.github.com/repos/tldr-pages/tldr/contents/pages/{platform}/{command}.md?ref=master";

    /**
     * @var CacheWriterInterface
     */
    private $cacheWriter;

    /**
     * RemoteFetcher constructor.
     *
     * @param \GuzzleHttp\Client $http
     */
    public function __construct(Client $http, CacheWriterInterface $cacheWriter = null)
    {
        $this->http = $http;
        $this->cacheWriter = $cacheWriter;
    }

    /**
     * Fetch a page from remote repository
     *
     * @param string $pageName
     * @return string
     * @throws PageNotFoundException
     */
    public function fetchPage(String $pageName): TldrPage
    {
        try {
            $response = $this->http->get($this->baseUrl);
        } catch (\Exception $e) {
            throw new RemoteFetcherException($e->getMessage());
        }

        $contents = json_decode($response->getBody()->getContents(), true);
        $pages = base64_decode($contents["content"]);
        $json = json_decode($pages, true);
        $pages = $json["commands"];

        $pages = $this->findPageInList($pageName, $pages);

        //working on assumption that only one page has been found!
        if (empty($pages)) {
            throw new PageNotFoundException;
        }

        $page = $pages["0"];
        $platform = $page["platform"][0];
        $url = str_replace("{platform}", $platform, $this->pageInfoUrlTemplate);
        $url = str_replace("{command}", $page["name"], $url);

        try {
            $response = $this->http->get($url);
        } catch (\Exception $e) {
            throw new RemoteFetcherException($e->getMessage());
        }

        $contents = json_decode($response->getBody()->getContents(), true);

        $pageContent = base64_decode($contents["content"]);

        $page = new TldrPage($pageName, $platform, $pageContent);

        if ($this->cacheWriter) {
            $this->cacheWriter->writeToCache($page);
        }

        return $page;
    }
}
