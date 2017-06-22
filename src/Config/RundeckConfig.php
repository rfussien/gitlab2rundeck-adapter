<?php

namespace G2R\Config;

class RundeckConfig extends AbstractConfig
{
    /**
     * The default config values.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'port'        => 4440,
            'ssl'         => false,
            'api_version' => 13,
            'log_level'   => 'WARN',
        ];
    }

    /**
     * Set the required fields.
     *
     * @return array
     */
    protected function getRequirements()
    {
        return [
            'host',
            'token',
        ];
    }
}
