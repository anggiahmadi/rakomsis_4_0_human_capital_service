<?php

namespace App\Traits;

trait FileHelper
{
    /**
     * Build checking string is base 64 or not
     * @param string $path
     * @return true|false
     */
    public function checkBase64($data)
    {
        $base64Parts = explode(";base64,", $data);

        return (sizeof($base64Parts) > 1);
    }
}
