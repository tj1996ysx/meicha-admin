<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SystemLog extends Model
{
    use CrudTrait;

    protected $table = 'system_logs';

    protected $guarded = [''];

    public static function record($request, $response)
    {
        $url = $request->url();
        if (Str::contains($url, '/reservation/fetch')) {
            return;
        }
        $log_type = Str::contains($url, '/admin') ? 'web' : 'api';
        $request_content = $request->headers.json_encode($request->all());

        $user_id = auth()->guest() ? null : auth()->user()->id;
        $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : $response->status();

        static::create([
            'log_type'   => $log_type,
            'url'        => $url,
            'method'     => $request->method(),
            'request'    => $request_content,
            'response'   => $response,
            'user_id'    => $user_id,
            'ip'         => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'status'     => $status,
        ]);
    }
}
