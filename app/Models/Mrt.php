<?php
/**
 * Created by PhpStorm.
 * User: rainlay
 * Date: 2015/8/24
 * Time: 下午 04:54
 */

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

class Mrt extends Model
{
    protected $fillable = ['uid', 'line', 'name', 'address', 'area', 'lat', 'lng'];
}