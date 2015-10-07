<?php namespace G2R\Gitlab;

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

        if (isset($data->object_kind)) {
            return new GitlabHook($data);
        }

        return new GitlabCiHook($data);
    }
}
