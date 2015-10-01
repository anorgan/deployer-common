<?php

namespace Deployer\Common\Server;

use Ssh\Configuration;
use Ssh\Session;
use Symfony\Component\Process\Process;

class Ssh extends AbstractServer
{
    private $user;

    /**
     * @param string $title
     * @param string $path
     * @param string $hostname
     * @param $user
     * @param LoggerInterface $logger
     */
    public function __construct($title, $path, $hostname, $user, LoggerInterface $logger = null)
    {
        parent::__construct($title, $path, $logger);

        $this->setHostname($hostname);
        $this->user = $user;
    }

    public function runCommands()
    {
        $commands = ['cd '.$this->getPath()];
        $commands = array_merge($commands, $this->getCommands());
        $commands = implode('; ', $commands);
        $process  = new Process($commands);

        $commandLine = $process->getCommandLine();
        $this->logger->info('Running "'.$commandLine.'"');
        $this->getSession()->getExec()->run($commandLine);
    }

    /**
     * @return Session
     */
    private function getSession()
    {
        $configuration  = new Configuration($this->getHostname());
        $authentication = new \Ssh\Authentication\Agent($this->user);
        $session        = new Session($configuration, $authentication);

        return $session;
    }
}
