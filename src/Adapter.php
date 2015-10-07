<?php namespace G2R;

use G2R\Config\ProjectConfig;
use G2R\Config\RundeckConfig;
use G2R\Exception\Exception;
use G2R\Gitlab\GitlabHook;
use G2R\Gitlab\HookResolver;
use G2R\Rundeck\JobRunner;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Adapter
{
    use LoggerTrait;

    protected $jobRunner;

    protected $projectsConfig;

    protected $hook;

    protected $input;

    public static function factory(
        $rundeckConfig,
        $projectConfig,
        $logger = null
    ) {
        $adapter = new self($logger);

        // attach jobRunner
        $adapter->attachJobRunner(
            new JobRunner(new RundeckConfig($rundeckConfig))
        );

        // load the projects config
        $adapter->loadProjectsConfig(
            new ProjectConfig($projectConfig)
        );

        return $adapter;
    }

    /**
     * Eventually attach a Logger
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Run the adapter
     *
     * @throws Exception
     */
    public function run()
    {
        if (!isset($this->projectsConfig)) {
            throw new Exception('The projects config is missing');
        }

        if (!isset($this->jobRunner)) {
            throw new Exception('The Job runner is missing');
        }

        $hook = $this->getHook();

        $project = $this->projectsConfig->getProject(
            $hook->getProjectName(),
            $hook->getRef()
        );

        // before we run the job, we want to make sure the build passed or
        // the runOnFail is true

        if ($this->getHook()->getBuildStatus() != 'success') {

            $this->warning(
                "The project {$hook->getProjectName()} build has failed"
            );

            if (!$project['runOnFail']) {

                $this->warning(
                    "The project config for {$hook->getProjectName()} " .
                    "doesn't enable to run the job when the build failed"
                );

                return 0;
            }
        }

        $this->info("The job ");

        return $this->jobRunner->run($project['jobId']);
    }

    /**
     * Load the rundeck config
     *
     * @param RundeckConfig $rundeckConfig
     */
    public function attachJobRunner(JobRunner $jobRunner)
    {
        $this->info(
            "Job runner uses '{$jobRunner->getConfig()->getFilename()}' config"
        );

        $this->jobRunner = $jobRunner;
    }

    /**
     * Load the projects config
     *
     * @param ProjectConfig $projectsConfig
     */
    public function loadProjectsConfig(ProjectConfig $projectsConfig)
    {
        $this->info(
            "Loading projects config file '{$projectsConfig->getFilename()}'"
        );

        $this->projectsConfig = $projectsConfig;
    }

    /**
     * Get the hook
     *
     * @return HookResolver
     */
    private function getHook()
    {
        if (!isset($this->hook)) {
            $this->hook = HookResolver::load($this->getInput());
        }

        return $this->hook;
    }

    /**
     * Get the input (php://input bu default)
     *
     * @return string
     */
    private function getInput()
    {
        if (!isset($this->input)) {
            $this->debug('no input specified. Default php://input');
            $this->input = file_get_contents('php://input');
        }

        return $this->input;
    }

    public function setHook(GitlabHook $hook)
    {
        $this->hook = $hook;
    }

    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * Call the logger
     *
     * @param       $level
     * @param       $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }
}
