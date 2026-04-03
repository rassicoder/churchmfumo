<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinancialSummaryRequest;
use App\Http\Requests\Finance\ListBudgetRequest;
use App\Http\Requests\Finance\StoreBudgetRequest;
use App\Http\Requests\Finance\UpdateBudgetRequest;
use App\Services\BudgetService;
use Illuminate\Http\JsonResponse;

class BudgetController extends Controller
{
    public function __construct(private readonly BudgetService $budgetService)
    {
    }

    public function index(ListBudgetRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $budgets = $this->budgetService->list($filters, $perPage);

        return response()->json([
            'data' => $budgets->items(),
            'meta' => [
                'current_page' => $budgets->currentPage(),
                'per_page' => $budgets->perPage(),
                'total' => $budgets->total(),
                'last_page' => $budgets->lastPage(),
            ],
        ]);
    }

    public function store(StoreBudgetRequest $request): JsonResponse
    {
        $budget = $this->budgetService->create($request->validated());

        return response()->json([
            'message' => 'Budget created successfully.',
            'data' => $budget,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $budget = $this->budgetService->show($id);

        if (! $budget) {
            return response()->json(['message' => 'Budget not found.'], 404);
        }

        return response()->json(['data' => $budget]);
    }

    public function update(UpdateBudgetRequest $request, string $id): JsonResponse
    {
        $budget = $this->budgetService->update($id, $request->validated());

        if (! $budget) {
            return response()->json(['message' => 'Budget not found.'], 404);
        }

        return response()->json([
            'message' => 'Budget updated successfully.',
            'data' => $budget,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->budgetService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Budget not found.'], 404);
        }

        return response()->json(['message' => 'Budget deleted successfully.']);
    }

    public function financialSummary(FinancialSummaryRequest $request): JsonResponse
    {
        $summary = $this->budgetService->financialSummary($request->validated());

        return response()->json(['data' => $summary]);
    }
}
