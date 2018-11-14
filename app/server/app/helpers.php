<?php

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     *
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath().'/config'.($path ? '/'.$path : $path);
    }
}

if (! function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}

if (! function_exists('isProductionEnv')) {
    /**
     * Check if we are in a production environment.
     *
     * @return string
     */
    function isProductionEnv()
    {
        return app()->environment('production');
    }
}

if (! function_exists('is_json')) {
    /**
     * Check if a string is a valid JSON
     *
     * NOTE: if decoding fails because JSON_ERROR_DEPTH it will still be considered a valid JSON string
     *
     * @param mixed $string
     *
     * @return bool
     */
    function is_json($string)
    {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);

        return in_array(json_last_error(), [
            JSON_ERROR_NONE,
            JSON_ERROR_DEPTH,
        ]);
    }
}
