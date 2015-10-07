<?php namespace G2R\Gitlab;

use G2R\TestCase;

class HookResolverTest extends TestCase
{
    public function testGitlabHookIsDetected()
    {
        $json = $this->loadFile('gitlab/push_event.json');
        $hook = HookResolver::load($json);

        $this->assertEquals('G2R\Gitlab\GitlabHook', get_class($hook));
    }

    public function testGitlabCiHookIsDetected()
    {
        $json = $this->loadFile('gitlabci/push_build_success.json');
        $hook = HookResolver::load($json);

        $this->assertEquals('G2R\Gitlab\GitlabCiHook', get_class($hook));
    }
}
