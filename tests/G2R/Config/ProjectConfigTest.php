<?php namespace G2R\Config;

use G2R\TestCase;

class ProjectConfigTest extends TestCase
{
    protected $config;

    public function setup()
    {
        $this->config = new ProjectConfig($this->locate('config/projects.yml'));
    }

    public function testGetTheConfigFilename()
    {
        $this->assertEquals(
            $this->locate('config/projects.yml'),
            $this->config->getFilename()
        );
    }

    public function testGetTheConfigForTheGivenProjectName()
    {
        $this->assertEquals(
            [
                'name' => 'repos/app1',
                'jobId' => '558d3c76-7768-4056-a10c-0842ecae0ca9',
                'ref' => 'master',
                'runOnFail' => true,
                'runOnTagOnly' => true,
                'jobArgs' => array (
                    'arg1' => 'foo',
                    'arg2' => 'bar',
                ),
                'runJobAs' => 'foo',
                'filter' => 'stagging',
            ],
            $this->config->getProject('repos/app1')
        );
    }

    public function testGetTheDefaultRefAndRunonfailValues()
    {
        $project = $this->config->getProject('repos/app3');

        $this->assertEquals('master', $project['ref']);
        $this->assertEquals(false, $project['runOnFail']);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Project foo/bar[master] not found
     */
    public function testAnExceptionIsThrownWhenTheProjectIsUnknown()
    {
        $this->config->getProject('foo/bar');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Duplicate project repos/duplicate[master]
     */
    public function testExceptionOnDuplicateProject()
    {
        $this->config->getProject('repos/duplicate');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage The jobId is missing for the repos/jobIdIsMissing[master] project
     */
    public function testTheProjectConfigChecking()
    {
        $this->config->getProject('repos/jobIdIsMissing');
    }
}
