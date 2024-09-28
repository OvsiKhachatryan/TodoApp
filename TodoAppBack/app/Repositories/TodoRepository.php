<?php
namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoRepository implements TodoRepositoryInterface
{
    protected $model;

    public function __construct(Todo $todo)
    {
        $this->model = $todo;
    }

    public function index()
    {
        return $this->model->where('user_id', Auth::id())->get(); // Only fetch todos for the authenticated user
    }

    public function store(array $data)
    {
        // Add the authenticated user's ID to the data
        $data['user_id'] = Auth::id();
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $todo = $this->model->find($id);

        if (!$todo || $todo->user_id !== Auth::id()) {
            return false; // Ensure the user can only update their own todos
        }

        $todo->update($data);
        return true;
    }

    public function destroy($id)
    {
        $todo = $this->model->find($id);

        if (!$todo || $todo->user_id !== Auth::id()) {
            return false; // Ensure the user can only delete their own todos
        }

        return $this->model->destroy($id);
    }
}
