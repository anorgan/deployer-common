<?php

namespace Deployer\Common;

use Deployer\Common\Server\AbstractServer;

class DeployStep
{
    private $title;
    private $commands;
    private $servers;
    private $path        = null;
    private $isMandatory = true;

    public function __construct($title, $commands, array $servers)
    {
        $this->title = $title;
        $this->setCommands($commands);
        $this->setServers($servers);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setCommands(array $commands)
    {
        $this->commands = $commands;
    }

    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @return AbstractServer[]
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @param array $servers
     */
    public function setServers(array $servers)
    {
        $this->servers = $servers;
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
     * @return bool
     */
    public function isMandatory()
    {
        return $this->isMandatory;
    }

    /**
     * @param bool $isMandatory
     */
    public function setIsMandatory($isMandatory)
    {
        $this->isMandatory = $isMandatory;
    }
}
