<?php namespace G2R\Gitlab;

use G2R\TestCase;

class GitlabCiHookTest extends TestCase
{
    protected $push_hook;
    protected $tag_hook;

    public function setup()
    {
        $this->push_hook = new GitlabCiHook(
            $this->loadFile('gitlabci/push_build_success.json')
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
        $this->assertEquals('success', $this->push_hook->getBuildStatus());
    }
}
