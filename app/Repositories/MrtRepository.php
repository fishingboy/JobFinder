<?php
/**
 * Created by PhpStorm.
 * User: rainlay
 * Date: 2015/8/24
 * Time: ¤U¤È 04:58
 */

namespace App\Repositories;

//use app\Models\Mrt;

use App\Models\Mrt;

class MrtRepository extends EloquentRepository
{
    /**
     * MrtRepository constructor.
     *
     * @param Mrt $model
     */
    public function __construct(Mrt $model)
    {
        $this->model = $model;
    }
}