<?php namespace G2R\Gitlab;

class GitlabCiHook extends AbstractHook
{
    public function getUrl()
    {
        return $this->data->gitlab_url;
    }

    public function getBuildStatus()
    {
        return $this->data->build_status;
    }
}
