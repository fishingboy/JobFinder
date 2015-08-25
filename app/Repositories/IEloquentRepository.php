<?php
/**
 * Created by PhpStorm.
 * User: rainlay
 * Date: 2015/8/25
 * Time: ¤W¤È 09:12
 */

namespace app\Repositories;


interface IEloquentRepository
{
    public function getModel();

    public function getAll();

    public function getById($id);

}