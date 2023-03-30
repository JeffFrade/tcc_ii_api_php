<?php

namespace App\Core\Support;

abstract class AbstractRepository
{
    protected $model;

    public function all(array $cols = ['*'])
    {
        return $this->model->withTrashed()->get($cols);
    }

    public function allNoTrashed(array $cols = ['*'])
    {
        return $this->model->get($cols);
    }

    public function paginate(int $perPage = 15, array $cols = ['*'])
    {
        return $this->model->paginate($perPage, $cols);
    }

    public function find($value)
    {
        return $this->model->where('id', $value)->get();
    }

    public function findFirst($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field, $value)->get($columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateCustom(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, $id)->update($data);
    }

    public function updateCustomMongoDb(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, $id)->update($data, ['upsert' => true]);
    }

    public function update(array $data, $id)
    {
        $instance = $this->model->find($id);
        $instance->fill($data);

        return $instance->save();
    }

    public function customDelete(string $field, string $value)
    {
        return $this->model->where($field, $value)->delete();
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function retrieveDelete($value, $field = 'id')
    {
        $instance = $this->model->onlyTrashed()
            ->where($field, $value)
            ->first();

        return $instance->restore();
    }
}
