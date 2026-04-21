<?php

namespace App\Http\Helpers;

trait ApiResponse
{
    private function apiResponse($code = 200, $error = null, $data = null, $meta = null)
    {
        $array = [
            'success' => ($code >= 200 && $code < 300) ? true : false

        ];

        if (!is_null($error)) {
            $array['error'] = $error;
        }

        if (!is_null($data)) {
            $array['data'] = $data;
        }

        if (!is_null($meta)) {
            $array['meta'] = $meta;
        }

        return response($array, $code);
    }
}
