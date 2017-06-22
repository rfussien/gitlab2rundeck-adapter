<?php

namespace G2R\Gitlab\Hook;

use G2R\TestCase;

class TagTest extends TestCase
{
    protected $hook;

    public function setup()
    {
        $this->hook = new Tag(
            $this->loadFile('gitlab/tag_event.json')
        );
    }

    public function testGetTheRefFromTheWebhook()
    {
        $this->assertEquals('v1.0.0', $this->hook->getRef());
    }

    public function testGetTheProjectName()
    {
        $this->assertEquals('repos/app1', $this->hook->getProjectName());
    }

    public function testGetTheBuildStatus()
    {
        $this->assertEquals('running', $this->hook->getBuildStatus());
    }
}
