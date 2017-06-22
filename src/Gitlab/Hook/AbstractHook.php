<?php

namespace G2R\Gitlab\Hook;

abstract class AbstractHook
{
    protected $hookContent;

    protected $buildStatus;

    public function __construct($hookContent)
    {
        $this->hookContent = $hookContent;
    }

    public function getContent()
    {
        if (gettype($this->hookContent) == 'string') {
            $this->hookContent = json_decode($this->hookContent);
        }

        return $this->hookContent;
    }

    public function getRef()
    {
        $ref = explode('/', $this->getContent()->ref);

        return array_pop($ref);
    }

    public function getProjectName()
    {
        preg_match(
            '/.*\/(.+\/.+)/',
            $this->getUrl(),
            $matches
        );

        return preg_replace('/\.git$/', '', array_pop($matches));
    }

    /**
     * Return the project url.
     *
     * @return string
     */
    abstract public function getUrl();

    /**
     * Return the Build status (success, failed, running).
     */
    abstract public function getBuildStatus();
}
