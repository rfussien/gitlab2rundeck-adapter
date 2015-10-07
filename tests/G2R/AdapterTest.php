<?php namespace G2R;

use G2R\Rundeck\JobRunner;

class AdapterTest extends TestCase
{
    protected $stubLogger;

    protected $stubGitlabHook;

    protected $stubJobRunner;

    protected $stubRundeckConfig;

    protected $stubProjectsConfig;

    protected $adapter;

    public function setup()
    {
        $this->stubLogger = $this->mockLogger();

        $this->stubGitlabHook = $this->mockGitlabHook();

        $this->stubRundeckConfig = $this->mockRundeckConfig();

        $this->stubJobRunner = $this->mockJobRunner($this->stubRundeckConfig);

        $this->stubProjectsConfig = $this->mockProjectsConfig();

        $this->adapter = new Adapter($this->stubLogger);
    }

    public function testTheLoggerIsLoadedAndDoLog()
    {
        $this->stubLogger
            ->expects($this->once())
            ->method('log');

        $this->adapter->info('test');
    }

    /**
     * @expectedException G2R\Exception\Exception
     * @expectedExceptionMessage The Job runner is missing
     */
    public function testAnExceptionIsThrownWhenNoJobRunnerIsAttached()
    {
        $this->adapter->loadProjectsConfig($this->stubProjectsConfig);

        $this->adapter->run();
    }

    /**
     * @expectedException G2R\Exception\Exception
     * @expectedExceptionMessage The projects config is missing
     */
    public function testAnExceptionIsThrownWhenNoProjectsConfigIsLoad()
    {
        $this->adapter->attachJobRunner($this->stubJobRunner);

        $this->adapter->run();
    }

    public function testTheAdaptaterRunsAJobCorrectly()
    {
        $project = $this->getProjectThatDoesNotRunOnFail();

        $this->stubGitlabHook
            ->method('getBuildStatus')
            ->willReturn('success');

        $this->stubProjectsConfig
            ->method('getProject')
            ->willReturn($project);

        $this->adapter->attachJobRunner($this->stubJobRunner);
        $this->adapter->loadProjectsConfig($this->stubProjectsConfig);
        $this->adapter->setHook($this->stubGitlabHook);

        $this->stubJobRunner
            ->expects($this->once())
            ->method('run');

        $this->adapter->run();
    }

    public function testTheAdaptaterRunsTheJobWhenRunonfailIsTrueEvenIfTheBuildFailed()
    {
        $project = $this->getProjectThatRunOnFail();

        $this->stubGitlabHook
            ->method('getBuildStatus')
            ->willReturn('failed');

        $this->stubProjectsConfig
            ->method('getProject')
            ->willReturn($project);

        $this->adapter->attachJobRunner($this->stubJobRunner);
        $this->adapter->loadProjectsConfig($this->stubProjectsConfig);
        $this->adapter->setHook($this->stubGitlabHook);

        $this->stubJobRunner
            ->expects($this->once())
            ->method('run');

        $this->adapter->run();
    }

    public function testTheAdaptaterDoesntRunTheJobWhenRunonfailIsFalseAndTheBuildFailed()
    {
        $project = $this->getProjectThatDoesNotRunOnFail();

        $this->stubGitlabHook
            ->method('getBuildStatus')
            ->willReturn('failed');

        $this->stubProjectsConfig
            ->method('getProject')
            ->willReturn($project);

        $this->adapter->attachJobRunner($this->stubJobRunner);
        $this->adapter->loadProjectsConfig($this->stubProjectsConfig);
        $this->adapter->setHook($this->stubGitlabHook);

        $this->stubJobRunner
            ->expects($this->never())
            ->method('run');

        $this->adapter->run();
    }

    private function mockLogger()
    {
        return $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->setMethods([
                'emergency',
                'alert',
                'critical',
                'error',
                'warning',
                'notice',
                'info',
                'debug',
                'log',
            ])
            ->getMock();
    }

    private function mockGitlabHook()
    {
        $mock = $this
            ->getMockBuilder('G2R\Gitlab\GitlabHook')
            ->disableOriginalConstructor()
            ->setMethods([
                'getBuildStatus',
                'getProjectName',
                'getRef',
                'getShortUrl',
            ])
            ->getMock();

        $mock->method('getShortUrl')->willReturn('repos/app1');

        $mock->method('getRef')->willReturn('master');

        $mock->method('getProjectName')->willReturn('repos/app1');

        return $mock;
    }

    private function mockRundeckConfig()
    {
        $mock = $this
            ->getMockBuilder('G2R\Config\RundeckConfig')
            ->disableOriginalConstructor()
            ->setMethods([
                'getApiUrl',
                'getFilename'
            ])
            ->getMock();

        $mock->method('getFilename')->willReturn(__DIR__);

        return $mock;
    }

    private function mockJobRunner($rundeckConfig)
    {
        $mock = $this
            ->getMockBuilder('G2R\Rundeck\JobRunner')
            ->disableOriginalConstructor()
            ->setMethods([
                'getConfig',
                'run'
            ])
            ->getMock();

        $mock->method('getConfig')->willReturn($rundeckConfig);

        return $mock;
    }

    private function mockProjectsConfig()
    {
        $mock = $this
            ->getMockBuilder('G2R\Config\ProjectConfig')
            ->disableOriginalConstructor()
            ->setMethods([
                'getProject',
                'getFilename'
            ])
            ->getMock();

        return $mock;
    }

    private function getProjectThatRunOnFail()
    {
        return [
            'name'         => 'repos/app1',
            'jobId'        => '558d3c76-7768-4056-a10c-0842ecae0ca9',
            'ref'          => 'master',
            'runOnFail'    => true,
            'runOnTagOnly' => true,
            'jobArgs'      =>
                array(
                    'arg1' => 'foo',
                    'arg2' => 'bar',
                ),
            'runJobAs'     => 'foo',
        ];
    }

    private function getProjectThatDoesNotRunOnFail()
    {
        return [
            'name'         => 'repos/app1',
            'jobId'        => '558d3c76-7768-4056-a10c-0842ecae0ca9',
            'ref'          => 'master',
            'runOnFail'    => false,
            'runOnTagOnly' => true,
            'jobArgs'      =>
                array(
                    'arg1' => 'foo',
                    'arg2' => 'bar',
                ),
            'runJobAs'     => 'foo',
        ];
    }
}
