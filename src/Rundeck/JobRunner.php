<?php

namespace G2R\Rundeck;

use G2R\Config\RundeckConfig;
use G2R\Exception\Exception;

class JobRunner
{
    protected $config;

    public function __construct(RundeckConfig $config)
    {
        $this->config = $config;
    }

    /**
     * return the Rundeck configuration.
     *
     * @return RundeckConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Send the request to rundesk.
     *
     * @param       $jobId
     * @param array $parameters
     */
    public function run($jobId, array $parameters = [])
    {
        $method = (empty($parameters)) ? 'GET' : 'POST';

        $options = [
            'http' => [
                'header' => "X-Rundeck-Auth-Token: {$this->config['token']}\r\n",
                'method' => $method,
            ],
        ];

        if ($method == 'POST') {
            $options['http']['header'] .= "Content-type: application/x-www-form-urlencoded\r\n";
            $options['http']['content'] = http_build_query($parameters);
        }

        $context = stream_context_create($options);

        return file_get_contents($this->getApiUrl($jobId), false, $context);
    }

    /**
     * Return the formatted url for a GET or POST rundeck request to run a job.
     *
     * http://rundeck.org/docs/api/index.html#running-a-job
     *      GET /api/1/job/[ID]/run
     *      POST /api/12/job/[ID]/executions
     *
     * @param        $jobId
     * @param string $method
     *
     * @throws Exception
     *
     * @return string
     */
    public function getApiUrl($jobId, $method = 'GET')
    {
        return
            'http'.(($this->config['ssl']) ? 's' : '').'://'.
            $this->config['host'].':'.$this->config['port'].'/api/'.
            $this->config['api_version'].'/job/'.$jobId.'/'.
            ((strcasecmp($method, 'get') == 0) ? 'run' : 'executions');
    }
}
