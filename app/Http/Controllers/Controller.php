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
     * @param string $message
     * @return json
     */
    protected function eApi(string $message = 'Error')
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
     * @param $payload
     * @return json
     */
    protected function aApi($payload)
    {
        $array = [
            'success' => true,
            'payload' => $payload
        ];

        return response()->json($array);
    }
}
