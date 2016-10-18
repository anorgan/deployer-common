<?php

namespace Anorgan\Deployer\Common\Server;

use Symfony\Component\Process\Process;

class Local extends AbstractServer
{
    public function runCommands()
    {
        $commands = implode('; ', $this->getCommands());

        $process = new Process($commands, $this->getPath());
        $process->setTimeout(600);
        $process->setIdleTimeout(null);

        $this->logger->info('Running "'.$process->getCommandLine().'"');
        $process->mustRun();
    }
}
