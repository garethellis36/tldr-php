<?php
declare(strict_types=1);

namespace GarethEllis\Tldr\Fetcher;

use GarethEllis\Tldr\Fetcher\Exception\PageNotFoundException;
use GarethEllis\Tldr\Fetcher\Exception\UnknownOperatingSystemException;

trait OperatingSystemTrait
{
    /**
     * @var array
     */
    private $options;

    protected function getOperatingSystem(array $options): String
    {
        if (isset($options["operatingSystem"])) {
            return $options["operatingSystem"];
        }
        $uname = strtolower(php_uname());
        if (strpos($uname, "darwin") !== false) {
            return "osx";
        } elseif (strpos($uname, "win") !== false) {
            return "windows";
        } elseif (strpos($uname, "linux") !== false) {
            return "linux";
        }
        throw new UnknownOperatingSystemException("Unknown operating system {$uname}");
    }
}
