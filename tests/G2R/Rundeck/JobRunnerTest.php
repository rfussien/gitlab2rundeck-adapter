<?php namespace G2R\Rundeck;

use G2R\Config\RundeckConfig;
use G2R\TestCase;

class JobRunnerTest extends TestCase
{
    protected $config;

    public function setup()
    {
        $this->config = new RundeckConfig($this->locate('config/rundeck.yml'));
    }

    public function testTheRundeckConfigIsAccessible()
    {
        $jobRunner = new JobRunner($this->config);

        $this->assertEquals($this->config, $jobRunner->getConfig());
    }

    public function testTheJobUrlIsGenerated()
    {
        $jobRunner = new JobRunner($this->config);
        $jobId = '558d3c76-7768-4056-a10c-0842ecae0ca8';
        $RunningJobApiGet = "https://rundeck.local:4440/api/13/job/{$jobId}/run";
        $RunningJobApiPost = "https://rundeck.local:4440/api/13/job/{$jobId}/executions";

        $this->assertEquals(
            $RunningJobApiGet,
            $jobRunner->getApiUrl($jobId)
        );

        $this->assertEquals(
            $RunningJobApiPost,
            $jobRunner->getApiUrl($jobId, "POST")
        );

        // check the method isn't case sensitive
        $this->assertEquals(
            $RunningJobApiPost,
            $jobRunner->getApiUrl($jobId, "pOsT")
        );
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unknown method. It must be GET or POST.
     */
    public function testAnExceptionIsThrownErrorOnUnknownMethod()
    {
        $JobRunner = new JobRunner($this->config);
        $jobId = '558d3c76-7768-4056-a10c-0842ecae0ca8';

        $JobRunner->getApiUrl($jobId, 'FOO');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unknown method. It must be GET or POST.
     */
    public function testErrorOnMethod()
    {
        $JobRunner = new JobRunner($this->config);
        $jobId = '558d3c76-7768-4056-a10c-0842ecae0ca8';

        $JobRunner->getApiUrl($jobId, ['GET']);
    }


}
