<?php namespace G2R\Gitlab;

use G2R\TestCase;

class GitlabHookTest extends TestCase
{
    protected $push_hook;
    protected $tag_hook;

    public function setup()
    {
        $this->push_hook = new GitlabHook(
            $this->loadFile('gitlab/push_event.json')
        );
    }

    public function testGetTheRefFromTheWebhook()
    {
        $this->assertEquals('master', $this->push_hook->getRef());
    }

    public function testGetTheProjectName()
    {
        $this->assertEquals('repos/app1', $this->push_hook->getProjectName());
    }

    public function testGetTheBuildStatus()
    {
        $this->assertEquals('n/a', $this->push_hook->getBuildStatus());
    }
}
