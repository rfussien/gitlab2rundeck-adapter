<?php namespace G2R\Gitlab\Hook;

class Push extends AbstractHook
{
    public function getUrl()
    {
        return $this->getContent()->repository->git_http_url;
    }

    public function getBuildStatus()
    {
        return 'n/a';
    }
}
