<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ServiceRegistry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ServiceRouter
{
    protected ServiceRegistry $serviceRegistry;

    public function __construct(ServiceRegistry $serviceRegistry)
    {
        $this->serviceRegistry = $serviceRegistry;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $timeCheckpoints = [];

        $path = $request->path();
        $service = $this->determineService($path);

        Log::info('Handling request', [
            'path' => $path,
            'service' => $service
        ]);

        if (!$service || !$this->serviceRegistry->isValidService($service)) {
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);
            Log::warning('Invalid service requested', [
                'path' => $path,
                'timing_ms' => $timeCheckpoints
            ]);
            return response()->json(['error' => 'Service not found'], 404);
        }

        $serviceUrl = $this->serviceRegistry->getServiceUrl($service);
        $targetPath = $this->getTargetPath($path, $service);
        $fullUrl = rtrim($serviceUrl, '/') . '/api/' . ltrim($targetPath, '/');

        // Allow health check endpoints without authentication
        if ($targetPath === 'health') {
            $forwardStartTime = microtime(true);
            $response = $this->forwardRequest($request, $fullUrl);
            $timeCheckpoints['service_forward'] = round((microtime(true) - $forwardStartTime) * 1000, 2);
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Health check completed', [
                'path' => $path,
                'timing_ms' => $timeCheckpoints
            ]);
            return $response;
        }

        // Allow public events endpoints without authentication
        if ($service === 'events' && ($targetPath === 'public' || preg_match('/^\d+$/', $targetPath))) {
            $forwardStartTime = microtime(true);
            $response = $this->forwardRequest($request, $fullUrl);
            $timeCheckpoints['service_forward'] = round((microtime(true) - $forwardStartTime) * 1000, 2);
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Public event request completed', [
                'path' => $path,
                'timing_ms' => $timeCheckpoints
            ]);
            return $response;
        }

        // For auth service requests, just forward them as is
        if ($service === 'auth') {
            $forwardStartTime = microtime(true);
            $response = $this->forwardRequest($request, $fullUrl);
            $timeCheckpoints['service_forward'] = round((microtime(true) - $forwardStartTime) * 1000, 2);
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Auth service request completed', [
                'path' => $path,
                'timing_ms' => $timeCheckpoints
            ]);
            return $response;
        }

        // For other services, validate token with auth service first
        $token = $request->bearerToken();
        if (!$token) {
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);
            Log::warning('No token provided', [
                'path' => $path,
                'timing_ms' => $timeCheckpoints
            ]);
            return response()->json(['message' => 'Unauthorized - No token provided'], 401);
        }

        try {
            // Call auth service to validate token and get user info
            $authStartTime = microtime(true);
            $authResponse = Http::timeout(3)
                ->withToken($token)
                ->get($this->serviceRegistry->getServiceUrl('auth') . '/api/user');
            $timeCheckpoints['auth_validation'] = round((microtime(true) - $authStartTime) * 1000, 2);

            if (!$authResponse->successful()) {
                $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);
                Log::warning('Invalid token', [
                    'path' => $path,
                    'timing_ms' => $timeCheckpoints
                ]);
                return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
            }

            $userData = $authResponse->json();

            // Add user role and ID headers for downstream services
            $request->headers->set('X-User-Role', strtolower($userData['role'] ?? ''));
            $request->headers->set('X-User-Id', (string)($userData['id'] ?? ''));

            Log::info('User authenticated', [
                'role' => $userData['role'] ?? null,
                'user_id' => $userData['id'] ?? null,
                'service' => $service
            ]);

            // Forward the request to the target service
            $forwardStartTime = microtime(true);
            $response = $this->forwardRequest($request, $fullUrl);
            $timeCheckpoints['service_forward'] = round((microtime(true) - $forwardStartTime) * 1000, 2);
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Request completed', [
                'path' => $path,
                'service' => $service,
                'status' => $response->status(),
                'timing_ms' => $timeCheckpoints
            ]);

            return $response;

        } catch (\Exception $e) {
            $timeCheckpoints['total'] = round((microtime(true) - $startTime) * 1000, 2);
            Log::error('Auth validation failed', [
                'error' => $e->getMessage(),
                'service' => $service,
                'timing_ms' => $timeCheckpoints
            ]);
            return response()->json(['message' => 'Authentication failed'], 401);
        }
    }

    protected function forwardRequest(Request $request, string $url): Response
    {
        try {
            $headers = $this->getForwardableHeaders($request);

            Log::info('Forwarding request', [
                'from' => $request->fullUrl(),
                'to' => $url,
                'method' => $request->method(),
                'headers' => $headers
            ]);

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->withToken($request->bearerToken())
                ->send(
                    $request->method(),
                    $url,
                    [
                        'query' => $request->query(),
                        'json' => $request->json()->all(),
                    ]
                );

            Log::info('Service response received', [
                'status' => $response->status(),
                'url' => $url
            ]);

            return response($response->body(), $response->status())
                ->withHeaders($this->getForwardableResponseHeaders($response));

        } catch (\Exception $e) {
            Log::error('Service request failed', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Service unavailable',
                'message' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 503);
        }
    }

    protected function determineService(string $path): ?string
    {
        $parts = explode('/', $path);
        // api/v1/service-name/...
        return count($parts) >= 3 ? $parts[2] : null;
    }

    protected function getTargetPath(string $path, string $service): string
    {
        $parts = explode('/', $path);
        // Skip api/v1/service-name
        return implode('/', array_slice($parts, 3));
    }

    protected function getForwardableHeaders(Request $request): array
    {
        $headers = [];
        foreach ($this->getForwardableHeaderNames() as $header) {
            if ($request->header($header)) {
                $headers[$header] = $request->header($header);
            }
        }

        return $headers;
    }

    protected function getForwardableResponseHeaders($response): array
    {
        $headers = [];
        foreach ($this->getForwardableHeaderNames() as $header) {
            if ($response->header($header)) {
                $headers[$header] = $response->header($header);
            }
        }
        return $headers;
    }

    protected function getForwardableHeaderNames(): array
    {
        return [
            'accept',
            'content-type',
            'authorization',
            'x-requested-with',
            'x-correlation-id',
            'x-user-role',
            'x-user-id'
        ];
    }
}
