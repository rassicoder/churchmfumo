<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionItem\ListActionItemRequest;
use App\Http\Requests\ActionItem\StoreActionItemRequest;
use App\Http\Requests\ActionItem\UpdateActionItemRequest;
use App\Services\ActionItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ActionItemController extends Controller
{
    public function __construct(private readonly ActionItemService $actionItemService)
    {
    }

    public function index(ListActionItemRequest $request, ?string $meetingId = null): JsonResponse
    {
        $filters = $request->validated();
        if ($meetingId) {
            $filters['meeting_id'] = $meetingId;
        }
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $items = $this->actionItemService->list($filters, $perPage);

        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function store(StoreActionItemRequest $request, string $meetingId): JsonResponse
    {
        $payload = $request->validated();
        $payload['meeting_id'] = $meetingId;

        try {
            $item = $this->actionItemService->create($payload);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $exception->errors(),
            ], 422);
        }

        return response()->json([
            'message' => 'Action item created successfully.',
            'data' => $item,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $item = $this->actionItemService->show($id);

        if (! $item) {
            return response()->json(['message' => 'Action item not found.'], 404);
        }

        return response()->json(['data' => $item]);
    }

    public function update(UpdateActionItemRequest $request, string $id): JsonResponse
    {
        try {
            $item = $this->actionItemService->update($id, $request->validated());
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $exception->errors(),
            ], 422);
        }

        if (! $item) {
            return response()->json(['message' => 'Action item not found.'], 404);
        }

        return response()->json([
            'message' => 'Action item updated successfully.',
            'data' => $item,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->actionItemService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Action item not found.'], 404);
        }

        return response()->json(['message' => 'Action item deleted successfully.']);
    }
}
