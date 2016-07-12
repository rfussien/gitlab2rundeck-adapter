<?php namespace G2R\Gitlab\Hook;

abstract class AbstractHook
{
    protected $hook;

    protected $buildStatus;

    public function __construct($hook)
    {
        if (gettype($hook) == 'string') {
            $hook = json_decode($hook);
        }

        $this->hook = $hook;
    }

    public function getRef()
    {
        $ref = explode('/', $this->hook->ref);

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
     * Return the project url
     * @return string
     */
    abstract public function getUrl();

    /**
     * Return the Build status (success, failed, running)
     */
    abstract public function getBuildStatus();
}
