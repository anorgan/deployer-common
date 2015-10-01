<?php

namespace Deployer\Common\Server;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractServer
{
    protected $title;
    protected $path;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected $hostname = null;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @param string          $title
     * @param string          $path
     * @param LoggerInterface $logger
     */
    public function __construct($title, $path, LoggerInterface $logger = null)
    {
        $this->title = $title;
        $this->path  = $path;

        if (null === $logger) {
            $logger = new NullLogger();
        }
        $this->logger = $logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param array $commands
     */
    public function setCommands(array $commands)
    {
        $this->commands = $commands;
    }

    abstract public function runCommands();
}
