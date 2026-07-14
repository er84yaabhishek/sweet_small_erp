<?php

namespace App\Repositories;

abstract class BaseRepository
{
    abstract protected function modelClass(): string;

    public function findById($id)
    {
        $modelClass = $this->modelClass();

        return $modelClass::findOrFail($id);
    }

    public function create(array $data)
    {
        $modelClass = $this->modelClass();

        return $modelClass::create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->findById($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }
}
