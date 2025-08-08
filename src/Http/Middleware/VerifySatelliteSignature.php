<?php

namespace AryaSvitkona\MetricSatellite\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySatelliteSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        // IP allow-list
        $allowed = config('metric-satellite.auth.allow_ips', []);
        if ($allowed && ! $this->ipAllowed($request->ip(), $allowed)) {
            abort(403, 'IP '.$request->ip().' not allowed');
        }

        // HMAC (preferred)
        $hmac = config('metric-satellite.auth.hmac.enabled');
        if ($hmac) {
            $key      = config('metric-satellite.auth.hmac.key');
            $algo     = config('metric-satellite.auth.hmac.algo', 'sha256');
            $header   = config('metric-satellite.auth.hmac.header', 'X-Signature');
            $tsHeader = config('metric-satellite.auth.hmac.ts_header', 'X-Signature-Timestamp');
            $skew     = (int) config('metric-satellite.auth.hmac.skew_seconds', 300);

            $ts = (int) $request->header($tsHeader, 0);
            if (! $ts || abs(time() - $ts) > $skew) {
                abort(401, 'Stale request');
            }

            $payload = $request->getMethod()."\n".$request->getPathInfo()."\n".$ts;
            $calc = hash_hmac($algo, $payload, (string) $key);
            $sig  = (string) $request->header($header);

            if (! hash_equals($calc, $sig)) {
                abort(401, 'Invalid signature');
            }

            return $next($request);
        }

        // Fallback: Bearer token
        $token = config('metric-satellite.auth.token');
        if (! $token || $request->bearerToken() !== $token) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }

    private function ipAllowed(string $ip, array $allowed): bool
    {
        foreach ($allowed as $rule) {
            $rule = trim($rule);
            if ($rule === $ip) return true;
            if (str_contains($rule, '/')) {
                if ($this->cidrMatch($ip, $rule)) return true;
            }
        }
        return false;
    }

    private function cidrMatch(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr);
        return (ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet);
    }
}
