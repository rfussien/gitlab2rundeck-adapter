<?php namespace G2R\Gitlab;

abstract class AbstractHook
{
    protected $data;

    protected $buildStatus;

    public function __construct($data)
    {
        if (gettype($data) == 'string') {
            $data = json_decode($data);
        }

        $this->data = $data;
    }

    public function getRef()
    {
        $ref = explode('/', $this->data->ref);

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

    abstract public function getUrl();
    abstract public function getBuildStatus();
}
