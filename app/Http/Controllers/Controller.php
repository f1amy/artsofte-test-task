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
     * Respond with JSON success message.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondApiSuccess()
    {
        return response()->json([
            'Message' => 'Success.',
        ]);
    }

    /**
     * Respond with JSON error message.
     *
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondApiError(string $message, int $status = 422)
    {
        return response()->json([
            'Errors' => [
                'Global' => $message,
            ],
        ], $status);
    }
}
