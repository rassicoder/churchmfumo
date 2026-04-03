<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFilterRequest;
use App\Services\ReportsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(private readonly ReportsService $reportsService)
    {
    }

    public function overview(ReportFilterRequest $request): JsonResponse
    {
        [$start, $end] = $this->reportsService->buildDateRange(
            $request->string('range')->toString(),
            $request->string('start_date')->toString(),
            $request->string('end_date')->toString()
        );

        $churchId = $this->resolveChurchId($request);

        $data = $this->reportsService->overview($churchId, $start, $end);

        return response()->json(['data' => $data]);
    }

    public function churchGrowth(ReportFilterRequest $request): JsonResponse
    {
        [$start, $end] = $this->reportsService->buildDateRange(
            $request->string('range')->toString(),
            $request->string('start_date')->toString(),
            $request->string('end_date')->toString()
        );

        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            return response()->json(['data' => $this->reportsService->leaderGrowth($user->church_id, $start, $end)]);
        }

        return response()->json(['data' => $this->reportsService->churchGrowth($start, $end)]);
    }

    public function finance(ReportFilterRequest $request): JsonResponse
    {
        [$start, $end] = $this->reportsService->buildDateRange(
            $request->string('range')->toString(),
            $request->string('start_date')->toString(),
            $request->string('end_date')->toString()
        );

        $churchId = $this->resolveChurchId($request);

        $data = $this->reportsService->finance($churchId, $start, $end);

        return response()->json(['data' => $data]);
    }

    public function exportCsv(ReportFilterRequest $request): StreamedResponse
    {
        [$start, $end] = $this->reportsService->buildDateRange(
            $request->string('range')->toString(),
            $request->string('start_date')->toString(),
            $request->string('end_date')->toString()
        );

        $churchId = $this->resolveChurchId($request);
        $overview = $this->reportsService->overview($churchId, $start, $end);
        $leaders = $this->reportsService->leaderGrowth($churchId, $start, $end);
        $finance = $this->reportsService->finance($churchId, $start, $end);

        $filename = 'reports-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($overview, $leaders, $finance) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Overview']);
            fputcsv($handle, array_keys($overview['totals']));
            fputcsv($handle, array_values($overview['totals']));

            fputcsv($handle, []);
            fputcsv($handle, ['Leader Growth']);
            fputcsv($handle, array_merge(['Month'], $leaders['labels']));
            fputcsv($handle, array_merge(['Total'], $leaders['data']));

            fputcsv($handle, []);
            fputcsv($handle, ['Finance']);
            fputcsv($handle, array_merge(['Month'], $finance['labels']));
            fputcsv($handle, array_merge(['Budget'], $finance['budgets']));
            fputcsv($handle, array_merge(['Expenses'], $finance['expenses']));
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function resolveChurchId(Request $request): ?string
    {
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            return $user->church_id;
        }

        return $request->string('church_id')->toString() ?: null;
    }
}
