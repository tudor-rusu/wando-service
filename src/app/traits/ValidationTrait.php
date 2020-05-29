<?php

namespace app\traits;

trait ValidationTrait
{

    /**
     * Sanitize strings
     *
     * @param $request
     *
     * @return mixed
     */
    public static function sanitizeString($request)
    {
        return filter_var(trim($request), FILTER_SANITIZE_STRING);
    }

}
