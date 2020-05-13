<?php

namespace App\Http\Controllers;

abstract class ApiController extends Controller
{
    public function success($data = null)
    {
        return response()->json($data);
    }

    public function fail($message = '', $code = 422, $errors = null)
    {
        return response()->json([
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
