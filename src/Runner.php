<?php

namespace Anorgan\Deployer\Common;

use Anorgan\Deployer\Common\Server\AbstractServer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Runner
{
    /**
     * @var DeployStep[]
     */
    private $steps;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DeployStep[]    $steps
     * @param LoggerInterface $logger
     */
    public function __construct(array $steps, LoggerInterface $logger = null)
    {
        $this->steps = $steps;

        if (null === $logger) {
            $logger = new NullLogger();
        }
        $this->logger = $logger;
    }

    /**
     * Run commands on every server of every step with steps' commands.
     */
    public function run()
    {
        foreach ($this->getSteps() as $step) {
            $this->logger->info('Starting "'.$step->getTitle().'"');

            foreach ($this->getServersForStep($step) as $server) {
                if ($step->isMandatory()) {
                    $server->runCommands();
                    $this->logger->info('Finished "'.$step->getTitle().'" on "'.$server->getTitle().'"');
                } else {
                    try {
                        $server->runCommands();
                        $this->logger->info('Finished "'.$step->getTitle().'" on "'.$server->getTitle().'"');
                    } catch (\Exception $e) {
                        $this->logger->info('Failed to run "'.$step->getTitle().'" on "'.$server->getTitle().'"');
                    }
                }
            }
        }
    }

    /**
     * @return DeployStep[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param DeployStep $step
     *
     * @return AbstractServer[]
     */
    public function getServersForStep(DeployStep $step)
    {
        $servers = $step->getServers();
        foreach ($servers as $server) {
            $server->setLogger($this->logger);
            $server->setCommands($step->getCommands());
        }

        return $servers;
    }
}
