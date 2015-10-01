<?php

namespace spec\Deployer\Common;

use Deployer\Common\DeployStep;
use Deployer\Common\Server\Local;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \InvalidArgumentException;

class DeployerSpec extends ObjectBehavior
{
    public function let()
    {
        $config = [
            'servers' => [
                'app1' => [
                    'path' => '/var/www/deployer'
                ]
            ],
            'steps' => [
                'Tests' => [
                    'commands' => [
                        'bin/phpspec run -fpretty'
                    ]
                ]
            ]
        ];
        $this->beConstructedThrough('create', [$config]);
    }

    function it_creates_runner_when_create_called_with_configuration()
    {
        $this->shouldHaveType('Deployer\Common\Runner');
    }

    function it_throws_exception_if_configuration_is_invalid()
    {
        $config = [];
        $this->beConstructedThrough('create', [$config]);
        $this->shouldThrow(new InvalidArgumentException('Error while creating Deploy Runner, config is empty'))
            ->duringInstantiation();
    }

    public function it_should_create_runner_with_correct_steps(DeployStep $step, Local $server)
    {
        $this->getSteps()->shouldHaveCount(1);
        $this->getSteps()[0]->shouldHaveType('\Deployer\Common\DeployStep');
        $this->getSteps()[0]->getTitle()->shouldReturn('Tests');
        $this->getSteps()[0]->isMandatory()->shouldBe(true);
        $this->getSteps()[0]->getCommands()->shouldReturn([
            'bin/phpspec run -fpretty'
        ]);

        $this->getSteps()[0]->getServers()->shouldHaveCount(1);
        $this->getSteps()[0]
            ->getServers()[0]->shouldHaveType('\Deployer\Common\Server\Local');
        $this->getSteps()[0]
            ->getServers()[0]->getTitle()->shouldReturn('app1');
        $this->getSteps()[0]
            ->getServers()[0]->getHostname()->shouldReturn('app1');
    }
}
