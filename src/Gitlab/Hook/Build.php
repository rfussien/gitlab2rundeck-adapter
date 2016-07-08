<?php namespace G2R\Gitlab\Hook;

class Build extends AbstractHook
{
    public function getUrl()
    {
        return $this->data->repository->git_http_url;
    }

    public function getBuildStatus()
    {
        return $this->data->build_status;
    }
}
