<?php

namespace Deployer\Common;

use Deployer\Common\Server\AbstractServer;
use Deployer\Common\Server\Local;
use Deployer\Common\Server\Ssh;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Deployer
{
    /**
     * @var AbstractServer[]
     */
    private $servers;

    /**
     * @var DeployStep[]
     */
    private $steps;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    private function getServersFromConfig(array $config)
    {
        $servers = [];
        foreach ($config['servers'] as $title => $serverConfig) {
            $server          = $this->createServerFromConfig($title, $serverConfig);
            $servers[$title] = $server;
        }

        return $this->servers = $servers;
    }

    private function getStepsFromConfig(array $config, array $servers)
    {
        $steps = [];
        foreach ($config['steps'] as $title => $stepConfig) {
            $steps[$title] = $this->createStepFromConfig($title, $stepConfig, $servers);
        }

        return $this->steps = $steps;
    }

    /**
     * @param string $title
     * @param array  $config
     *
     * @return AbstractServer
     */
    private function createServerFromConfig($title, array $config)
    {
        $path = $config['path'];

        switch (true) {
            case isset($config['user']):
            case isset($config['host']):
                $server = new Ssh($title, $path, $config['host'], $config['user']);

                break;

            default:
                $server = new Local($title, $path);
                $server->setHostname($title);

                break;
        }

        return $server;
    }

    /**
     * @param string $title
     * @param array  $config
     * @param array  $allServers
     *
     * @return DeployStep
     */
    private function createStepFromConfig($title, array $config, array $allServers)
    {
        $commands = $config['commands'];

        if (!isset($config['servers']) || $config['servers'] === 'all') {
            // Run on all servers
            $servers = array_values($allServers);
        } else {
            $servers           = [];
            $config['servers'] = (array) $config['servers'];
            foreach ($config['servers'] as $serverTitle) {
                $servers[] = $allServers[$serverTitle];
            }
        }

        $step = new DeployStep($title, $commands, $servers);

        if (isset($config['mandatory'])) {
            $step->setIsMandatory($config['mandatory']);
        }

        return $step;
    }

    public static function create(array $config, LoggerInterface $logger = null)
    {
        if (empty($config)) {
            throw new \InvalidArgumentException('Error while creating Deploy Runner, config is empty');
        }

        if (null === $logger) {
            $logger = new NullLogger();
        }

        $self = new self();
        $self->setLogger($logger);

        $servers = $self->getServersFromConfig($config);

        $steps = $self->getStepsFromConfig($config, $servers);

        return new Runner(array_values($steps), $logger);
    }
}
