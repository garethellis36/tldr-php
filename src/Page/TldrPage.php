<?php

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
    private $os;

    /**
     * @var String
     */
    private $content;

    public function __construct(String $name, String $os, String $content)
    {
        $this->name = $name;
        $this->os = $os;
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
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @return String
     */
    public function getContent()
    {
        return $this->content;
    }
}
