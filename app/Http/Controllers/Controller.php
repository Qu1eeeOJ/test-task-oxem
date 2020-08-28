<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Call if the request was processed unsuccessfully
     *
     * @param object|string $message
     * @return json
     */
    protected function eApi($message = 'Error')
    {
        $array = [
            'success' => false,
            'error' => $message
        ];

        return response()->json($array);
    }

    /**
     * Call if the request was processed successfully
     *
     * @param mixed|null $payload
     * @return json
     */
    protected function sApi($payload = null)
    {
        $array = [
            'success' => true,
            'payload' => !is_null($payload) ? $payload : []
        ];

        return response()->json($array);
    }
}
