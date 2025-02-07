<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignedUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // APP_URL のホスト名を取得
        $appUrlHost = parse_url(config('app.url'), PHP_URL_HOST);
        $requestHost = $request->getHost();

        // ホスト名をAPP_URLのホストに統一
        if ($requestHost !== $appUrlHost) {
            $request->headers->set('host', $appUrlHost);
        }

        // 署名を検証
        if (! URL::hasValidSignature($request)) {
            abort(Response::HTTP_FORBIDDEN, 'Invalid or expired link.');
        }

        return $next($request);
    }
}
