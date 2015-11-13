<?php namespace G2R\Gitlab;

class GitlabHook extends AbstractHook
{
    public function getUrl()
    {
        return $this->data->repository->git_http_url;
    }

    public function getBuildStatus()
    {
        return 'n/a';
    }
}
