<?php
/**
 * Created by PhpStorm.
 * User: rainlay
 * Date: 2015/8/25
 * Time: ¤W¤È 09:20
 */

namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository implements IEloquentRepository
{
    protected $model;

    /**
     * EloquentRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getNewModel($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }
}