<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    /**
     * 省市县数据
     * @throws \Exception
     */
    public function index(Request $request){
        return city_cache($request->query('pid',0));
    }
}
