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
     * @param string $hookContent
     *
     * @return GitlabCiHook|GitlabHook
     */
    public static function load($hookContent)
    {
        $hookContent = self::objectValidation($hookContent);

        if ($hookContent->object_kind === 'push') {
            return new Push($hookContent);
        }

        if ($hookContent->tag) {
            return new Tag($hookContent);
        }

        return new Build($hookContent);
    }

    /**
     * Check if the hook content has a valid object kind
     *
     * @return string $hookContent
     */
    private static function objectValidation($hookContent)
    {
        $hookContent = json_decode($hookContent);

        if (!isset($hookContent->object_kind)) {
            throw new Exception('Object kind not found in the hook data');
        }

        if (!in_array($hookContent->object_kind, ['push', 'build'])) {
            throw new Exception('Unknown Object kind from the hook');
        }

        return $hookContent;
    }
}
