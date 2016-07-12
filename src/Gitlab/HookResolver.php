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
     * @param string $data
     *
     * @return GitlabCiHook|GitlabHook
     */
    public static function load($hook)
    {
        $hook = self::objectValidation($hook);

        if ($hook->object_kind === 'push') {
            return new Push($hook);
        }

        if ($hook->tag) {
            return new Tag($hook);
        }

        return new Build($hook);
    }

    /**
     * Check if the hook content has a valid object kind
     *
     * @return string $hook
     */
    private static function objectValidation($hook)
    {
        $hook = json_decode($hook);

        if (!isset($hook->object_kind)) {
            throw new Exception('Object kind not found in the hook data');
        }

        if (!in_array($hook->object_kind, ['push', 'build'])) {
            throw new Exception('Unknown Object kind from the hook');
        }

        return $hook;
    }
}
