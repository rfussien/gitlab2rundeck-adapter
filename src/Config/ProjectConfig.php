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
     * Return a project identified by its short url
     *
     * @param        $name
     * @param string $ref
     *
     * @return null
     * @throws Exception
     */
    public function getProject($name, $ref = 'master')
    {
        $project = array_filter(
            $this->get('projects'),
            function ($config) use ($name, $ref) {
                return
                    $config['project']['name'] == $name &&
                    $config['project']['ref'] == $ref;
            }
        );

        switch (count($project)) {
            case 1:
                $project = array_shift($project)['project'];

                if (!isset($project['jobId'])) {
                    throw new Exception(
                        "The jobId is missing for the {$name}[{$ref}] project"
                    );
                }

                return $project;
            case 0:
                throw new Exception("Project {$name}[{$ref}] not found");
            default:
                throw new Exception("Duplicate project {$name}[{$ref}]");
        }
    }
}
