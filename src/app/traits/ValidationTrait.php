<?php

namespace app\traits;

trait ValidationTrait
{

    /**
     * Sanitize strings
     *
     * @param string $request
     *
     * @return mixed
     */
    public static function sanitizeString(string $request)
    {
        return filter_var(trim($request), FILTER_SANITIZE_STRING);
    }

}
