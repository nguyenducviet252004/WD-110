<?php

namespace App\Http\Middleware;

use Closure;

class ConvertFormDataForPut
{
    public function handle($request, Closure $next)
    {
        if (in_array($request->method(), ['PUT', 'PATCH']) && strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
            foreach ($_POST as $key => $value) {
                $request->request->set($key, $value);
            }
        }
        return $next($request);
    }
}
