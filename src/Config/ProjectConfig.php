<?php namespace G2R\Config;

use G2R\Exception\Exception;

class ProjectConfig extends AbstractConfig
{
    public function __construct($path)
    {
        parent::__construct($path);

        $this->setProjectDefaults();
    }

    protected function getDefaults()
    {
        return [
            'base_url' => 'http://gitlab.local',
        ];
    }

    protected function getRequirements()
    {
        return [
            'base_url',
            'projects',
        ];
    }

    protected function getProjectDefaults()
    {
        return [
            'ref'       => 'master',
            'runOnFail' => false,
        ];
    }

    protected function setProjectDefaults()
    {
        $projects = $this['projects'];

        array_walk($projects, function (&$project) {
            $project['project'] = array_merge(
                $this->getProjectDefaults(),
                $project['project']
            );
        });

        $this['projects'] = $projects;
    }

    /**
     * Try to find a project and return it
     *
     * @return array $project
     */
    protected function findProject($name, $ref)
    {
        $projects = array_filter(
            $this->get('projects'),
            function ($config) use ($name, $ref) {
                return
                    $config['project']['name'] == $name &&
                    $config['project']['ref'] == $ref;
            }
        );

        if (count($projects) > 1) {
            throw new Exception("Duplicate project {$name}[{$ref}]");
        }

        if (count($projects) === 0) {
            throw new Exception("Project {$name}[{$ref}] not found");
        }

        return array_shift($projects)['project'];
    }

    /**
     * Return a project identified by its short url
     *
     * @param        $name
     * @param string $ref
     *
     * @return array $project
     * @throws Exception
     */
    public function getProject($name, $ref = 'master')
    {
        $project = $this->findProject($name, $ref);

        if (!isset($project['jobId'])) {
            throw new Exception(
                "The jobId is missing for the {$name}[{$ref}] project"
            );
        }

        return $project;
    }
}
