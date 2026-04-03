<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogMiddleware
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldLog($request, $response)) {
            return $response;
        }

        [$table, $recordId] = $this->resolveTarget($request);
        $action = sprintf('%s %s', $request->method(), $request->path());

        $this->logger->log($request, $action, $table, $recordId);

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return false;
        }

        return $response->getStatusCode() < 400;
    }

    private function resolveTarget(Request $request): array
    {
        $segments = $request->segments();

        // Expected: /api/v1/{resource}/{id}
        $resource = $segments[2] ?? ($segments[1] ?? 'unknown');
        $recordId = $request->route('id')
            ?? $request->route('meetingId')
            ?? $request->route('projectId')
            ?? $request->route('leaderId')
            ?? $request->route('departmentId');

        return [$resource, $recordId];
    }
}
