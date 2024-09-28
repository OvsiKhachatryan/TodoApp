<?php
namespace App\Http\Controllers;

use App\Services\TodoServiceInterface;
use App\Http\Requests\TodoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoServiceInterface $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index(): JsonResponse
    {
        $todos = $this->todoService->index();
        return response()->json($todos);
    }

    public function store(TodoRequest $request): JsonResponse
    {
        $data = $request->only(['name']);
        $todo = $this->todoService->store($data);
        return response()->json($todo, 201);
    }

    public function update(TodoRequest $request, $id): JsonResponse
    {
        $data = [];

        if ($request->has('name')) {
            $data['name'] = $request->input('name');
        }

        if ($request->has('status')) {
            $data['status'] = $request->input('status');
        }

        if (count($data) !== 1) {
            return response()->json(['error' => 'You must provide either name or status, but not both'], 422);
        }

        $updated = $this->todoService->update($id, $data);

        if (!$updated) {
            return response()->json(['error' => 'Todo not found or you are not authorized to update this todo'], 404);
        }

        return response()->json(['success' => true], 200);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->todoService->destroy($id);

        if (!$deleted) {
            return response()->json(['error' => 'Todo not found or you are not authorized to delete this todo'], 404);
        }

        return response()->json(['success' => true], 200);
    }
}
