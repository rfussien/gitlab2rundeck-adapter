<?php namespace G2R\Gitlab;

class GitlabCiHook extends GitlabHook
{
    public function __construct($data)
    {
        if (gettype($data) == 'string') {
            $data = json_decode($data);
        }

        $this->pushData = $data->push_data;
        $this->buildStatus = $data->build_status;
    }
}
