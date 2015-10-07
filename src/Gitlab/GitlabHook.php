<?php namespace G2R\Gitlab;

class GitlabHook
{
    protected $pushData;

    protected $buildStatus = 'failed';

    public function __construct($data)
    {
        if (gettype($data) == 'string') {
            $data = json_decode($data);
        }

        $this->pushData = $data;
    }

    public function getRef()
    {
        $ref = explode('/', $this->pushData->ref);

        return array_pop($ref);
    }

    public function getProjectName()
    {
        $matches = [];

        preg_match(
            "/\w+\@.*:(.*)\.git/",
            $this->pushData->repository->url,
            $matches
        );

        return $matches[1];
    }

    public function getBuildStatus()
    {
        return $this->buildStatus;
    }
}
