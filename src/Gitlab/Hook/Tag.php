<?php namespace G2R\Gitlab\Hook;

class Tag extends AbstractHook
{
    public function getUrl()
    {
        return $this->getContent()->repository->git_http_url;
    }

    public function getBuildStatus()
    {
        return $this->getContent()->build_status;
    }
}
