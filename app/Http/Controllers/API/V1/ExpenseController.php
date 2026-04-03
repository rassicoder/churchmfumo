<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\ListExpenseRequest;
use App\Http\Requests\Finance\StoreExpenseRequest;
use App\Http\Requests\Finance\UpdateExpenseRequest;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    public function __construct(private readonly ExpenseService $expenseService)
    {
    }

    public function index(ListExpenseRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $expenses = $this->expenseService->list($filters, $perPage);

        return response()->json([
            'data' => $expenses->items(),
            'meta' => [
                'current_page' => $expenses->currentPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'last_page' => $expenses->lastPage(),
            ],
        ]);
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->expenseService->create($request->validated());

        return response()->json([
            'message' => 'Expense created successfully.',
            'data' => $expense,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $expense = $this->expenseService->show($id);

        if (! $expense) {
            return response()->json(['message' => 'Expense not found.'], 404);
        }

        return response()->json(['data' => $expense]);
    }

    public function update(UpdateExpenseRequest $request, string $id): JsonResponse
    {
        $expense = $this->expenseService->update($id, $request->validated());

        if (! $expense) {
            return response()->json(['message' => 'Expense not found.'], 404);
        }

        return response()->json([
            'message' => 'Expense updated successfully.',
            'data' => $expense,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->expenseService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Expense not found.'], 404);
        }

        return response()->json(['message' => 'Expense deleted successfully.']);
    }
}
