<?php

namespace spec\Anorgan\Deployer\Common;

use Anorgan\Deployer\Common\DeployStep;
use Anorgan\Deployer\Common\Server\Local;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class RunnerSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger, DeployStep $step1, DeployStep $step2, Local $server1, Local $server2)
    {
        $server1->getTitle()->willReturn('app1');
        $server2->getTitle()->willReturn('app2');

        $step1->getTitle()->willReturn('Step 1');
        $step1->getServers()->willReturn([]);
        $step2->getTitle()->willReturn('Step 2');
        $step2->getServers()->willReturn([]);

        $step1->getServers()->willReturn([
            $server1,
            $server2
        ]);
        $step2->getServers()->willReturn([
            $server1
        ]);

        $step1->getCommands()->willReturn(['echo "step 1: command 1"', 'echo "step 1: command 2"']);
        $step2->getCommands()->willReturn(['echo "step 2: command 1"', 'echo "step 2: command 2"']);

        $steps = [
            $step1,
            $step2,
        ];
        $this->beConstructedWith($steps, $logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Anorgan\Deployer\Common\Runner');
    }

    public function BAK_it_runs_commands_in_correct_order_and_logs_them(LoggerInterface $logger)
    {
        $logger->info('Starting "Step 1"')->shouldBeCalled();
        $logger->info('Running "echo "step 1: command 1""')->shouldBeCalled();
        $logger->info('Running "echo "step 1: command 2""')->shouldBeCalled();
        $logger->info('Finished "Step 1"')->shouldBeCalled();

        $logger->info('Starting "Step 2"')->shouldBeCalled();
        $logger->info('Running "echo "step 2: command 1""')->shouldBeCalled();
        $logger->info('Running "echo "step 2: command 2""')->shouldBeCalled();
        $logger->info('Finished "Step 2"')->shouldBeCalled();

        $this->run();
    }

    public function it_gets_servers_for_each_step_and_sets_commands(LoggerInterface $logger, DeployStep $step1, DeployStep $step2, Local $server1, Local $server2)
    {
        $server1->setCommands(['echo "step 1: command 1"', 'echo "step 1: command 2"'])->shouldBeCalled();
        $server2->setCommands(['echo "step 1: command 1"', 'echo "step 1: command 2"'])->shouldBeCalled();

        $server1->setCommands(['echo "step 2: command 1"', 'echo "step 2: command 2"'])->shouldBeCalled();
        $server1->setLogger($logger)->shouldBeCalled();
        $server2->setLogger($logger)->shouldBeCalled();

        $step1->getServers()->willReturn([
            $server1,
            $server2
        ]);
        $step2->getServers()->willReturn([
            $server1,
        ]);
        $this->getServersForStep($step1)->shouldReturn([
            $server1,
            $server2
        ]);

        $this->getServersForStep($step2)->shouldReturn([
            $server1,
        ]);
    }
}
