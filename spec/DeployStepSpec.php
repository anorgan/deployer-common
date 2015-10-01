<?php

namespace spec\Deployer;

use Deployer\Server\Local;
use PhpSpec\ObjectBehavior;

class DeployStepSpec extends ObjectBehavior
{
    private $commands = [
        'ls -altr',
        'echo "test"',
    ];

    public function let(Local $server1, Local $server2)
    {
        $servers = [
            $server1,
            $server2
        ];
        $this->beConstructedWith('Step 1', $this->commands, $servers);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Deployer\DeployStep');
    }

    public function it_has_title()
    {
        $this->getTitle()->shouldReturn('Step 1');
    }

    public function it_returns_commands()
    {
        $this->getCommands()->shouldEqual($this->commands);
    }

    public function it_can_set_servers_on_which_to_run()
    {
        $this->setServers(['a', 'b']);
        $this->getServers()->shouldEqual(['a', 'b']);
    }

    public function it_is_mandatory_by_default()
    {
        $this->isMandatory()->shouldBe(true);
    }

    public function it_can_be_optional()
    {
        $this->setIsMandatory(false);
        $this->isMandatory()->shouldBe(false);
    }
}
