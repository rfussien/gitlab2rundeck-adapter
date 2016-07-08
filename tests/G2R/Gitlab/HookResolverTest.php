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

    /**
     * @expectedException G2R\Exception\Exception
     * @expectedExceptionMessage Object kind not found in the hook data
     */
    public function testAnExceptionIsThrownWhenNoObjectKindIsFound()
    {
        $hook = HookResolver::load('{}');
    }

    /**
     * @expectedException G2R\Exception\Exception
     * @expectedExceptionMessage Unknown Object kind from the hook
     */
    public function testAnExceptionIsThrownWhenTheObjectKindIsUnknown()
    {
        $hook = HookResolver::load('{"object_kind": "WhatTheHeckIsThat?"}');
    }
}
