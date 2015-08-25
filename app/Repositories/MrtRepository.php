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

    public function getGroupLineStations()
    {
        $data = $this->getAll();

        foreach($data as $station)
        {
            $result[$station->line][]  =
            [
                "uid" => $station->uid,
                "name" => $station->name,
                "area" => $station->area,
                "lat" => $station->lat,
                "lng" => $station->lng,
                "address" => $station->address,
            ];
        }

        return $result;
//        echo "<PRE>";
//        var_dump($result);
//        echo "</PRE>";
    }
}