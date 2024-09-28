<?php

namespace App\Services;

use App\Repositories\TodoRepositoryInterface;

class TodoService implements TodoServiceInterface
{
    protected $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function index()
    {
        return $this->todoRepository->index();
    }

    public function store(array $data)
    {
        return $this->todoRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->todoRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->todoRepository->destroy($id);
    }
}
