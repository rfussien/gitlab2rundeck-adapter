<?php namespace G2R\Config;

use G2R\TestCase;

class RundeckConfigTest extends TestCase
{
    public function testCheckTheDefaultValues()
    {
        $config = new RundeckConfig($this->locate('config/rundeck_mini.yml'));

        $this->assertEquals('rundeck.local', $config['host']);
        $this->assertEquals('4440', $config['port']);
        $this->assertEquals(false, $config['ssl']);
        $this->assertEquals('13', $config['api_version']);
        $this->assertEquals('WARN', $config['log_level']);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessageRegExp /The field host is missing in/
     */
    public function testAnExceptionIsThrownOnMissingField()
    {
        new RundeckConfig($this->locate('config/rundeck_fail.yml'));
    }
}
