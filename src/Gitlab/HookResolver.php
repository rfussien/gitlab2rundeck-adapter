<?php namespace G2R\Gitlab;

use G2R\Exception\Exception;
use G2R\Gitlab\Hook\Build;
use G2R\Gitlab\Hook\Push;
use G2R\Gitlab\Hook\Tag;

class HookResolver
{
    /**
     * Load a gitlab or gitlab CI webhook handler
     *
     * @param $data
     *
     * @return GitlabCiHook|GitlabHook
     */
    public static function load($data)
    {
        $data = json_decode($data);

        if (!isset($data->object_kind)) {
            throw new Exception('Object kind not found in the hook data');
        }

        if (!in_array($data->object_kind, ['push', 'build'])) {
            throw new Exception('Unknown Object kind from the hook');
        }

        if ($data->object_kind === 'push') {
            return new Push($data);
        }

        if ($data->tag) {
            return new Tag($data);
        }

        return new Build($data);
    }
}
