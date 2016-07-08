<?php namespace G2R\Gitlab;

use G2R\TestCase;

class HookResolverTest extends TestCase
{
    public function testHookPushIsResolved()
    {
        $json = $this->loadFile('gitlab/push_event.json');
        $hook = HookResolver::load($json);

        $this->assertInstanceOf('G2R\Gitlab\Hook\Push', $hook);
    }

    public function testHookTagIsResolved()
    {
        $json = $this->loadFile('gitlab/tag_event.json');
        $hook = HookResolver::load($json);

        $this->assertInstanceOf('G2R\Gitlab\Hook\Tag', $hook);
    }

    public function testHookBuildIsResolved()
    {
        $json = $this->loadFile('gitlab/build_event.json');
        $hook = HookResolver::load($json);

        $this->assertInstanceOf('G2R\Gitlab\Hook\Build', $hook);
    }
}
