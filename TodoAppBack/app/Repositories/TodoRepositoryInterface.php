<?php

namespace App\Repositories;

interface TodoRepositoryInterface
{
    public function index();

    public function store(array $data);

    public function update($id, array $data);

    public function destroy($id);
}
