<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Page;

class TldrPage
{
    /**
     * @var String
     */
    private $name;

    /**
     * @var String
     */
    private $platform;

    /**
     * @var String
     */
    private $content;

    public function __construct(String $name, String $platform, String $content)
    {
        $this->name = $name;
        $this->platform = $platform;
        $this->content = $content;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return String
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @return String
     */
    public function getContent()
    {
        return $this->content;
    }
}
