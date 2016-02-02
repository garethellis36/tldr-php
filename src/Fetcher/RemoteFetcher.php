<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Page\TldrPage;
use GuzzleHttp\Client;
use GarethEllis\Tldr\Fetcher\Exception\RemoteFetcherException;
use Metadata\Cache\CacheInterface;

/**
 * Class RemoteFetcher
 *
 * Fetches a page from the remote repository and optionally caches the result
 *
 * @package GarethEllis\Tldr\Fetcher
 */
class RemoteFetcher implements PageFetcherInterface
{
    use OperatingSystemTrait;

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
     * @var array
     */
    private $options;

    /**
     * RemoteFetcher constructor.
     *
     * @param \GuzzleHttp\Client $http
     */
    public function __construct(Client $http, array $options = [])
    {
        $this->http = $http;
        $this->options = $options;
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
        $page = $this->findPageInList($pageName, $pages);

        $url = str_replace("{platform}", $page["platform"], $this->pageInfoUrlTemplate);
        $url = str_replace("{command}", $page["name"], $url);

        try {
            $response = $this->http->get($url);
        } catch (\Exception $e) {
            throw new RemoteFetcherException($e->getMessage());
        }

        $contents = json_decode($response->getBody()->getContents(), true);
        $pageContent = base64_decode($contents["content"]);
        $page = new TldrPage($pageName, $page["platform"], $pageContent);

        return $page;
    }

    protected function findPageInList(String $page, array $list)
    {
        $filtered = array_filter($list, function ($foundPage) use ($page) {
            return $foundPage["name"] === $page;
        });

        if (empty($filtered)) {
            throw new PageNotFoundException;
        }

        $page = array_shift($filtered);

        foreach ($page["platform"] as $k => $platform) {

            if ($platform === $this->getOperatingSystem()) {
                $platformSpecificKey = $k;
                continue;
            }

            if ($platform === "common") {
                $commonKey = $k;
                continue;
            }
        }

        if (!isset($commonKey) && !isset($platformSpecificKey)) {
            throw new PageNotFoundException();
        }

        $key = $platformSpecificKey ?? $commonKey;
        $page["platform"] = $page["platform"][$key];

        return $page;
    }
}
