<?php

namespace G2R\Gitlab\Hook;

use G2R\TestCase;

class PushTest extends TestCase
{
    protected $hook;

    public function setup()
    {
        $this->hook = new Push(
            $this->loadFile('gitlab/push_event.json')
        );
    }

    public function testGetTheRefFromTheWebhook()
    {
        $this->assertEquals('master', $this->hook->getRef());
    }

    public function testGetTheProjectName()
    {
        $this->assertEquals('repos/app1', $this->hook->getProjectName());
    }

    public function testGetTheBuildStatus()
    {
        $this->assertEquals('n/a', $this->hook->getBuildStatus());
    }
}
