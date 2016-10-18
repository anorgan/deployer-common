<?php

namespace spec\Anorgan\Deployer\Common\Server;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class LocalSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger)
    {
        $title = 'Local';
        $path  = '/var/www/deployer';
        $this->beConstructedWith($title, $path, $logger);
    }

    public function it_is_abstract()
    {
        $this->shouldHaveType('Anorgan\Deployer\Common\Server\Local');
    }

    public function it_can_set_commands()
    {
        $commands = [
            'command 1',
            'command 2',
            'command 3',
        ];
        $this->getCommands()->shouldReturn([]);
        $this->setCommands($commands);
        $this->getCommands()->shouldReturn($commands);
    }

    public function it_runs_and_logs_commands(LoggerInterface $logger)
    {
        $this->setCommands([
            'echo 1',
            'echo 2',
        ]);
        $logger->info('Running "echo 1; echo 2"')->shouldBeCalled();

        $this->runCommands();
    }
}
