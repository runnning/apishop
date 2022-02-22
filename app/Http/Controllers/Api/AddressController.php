<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\AddressRequest;
use App\Models\Address;
use App\Transformers\AddressTransformer;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseController
{
    /**
     *我的地址列表
     */
    public function index(): \Dingo\Api\Http\Response
    {
        $address=Address::where('user_id',auth('api')->id())->get();
        return $this->response->collection($address,new AddressTransformer());
    }

    /**
     * 添加地址
     */
    public function store(AddressRequest $request): \Dingo\Api\Http\Response
    {
        $request->offsetSet('user_id',auth('api')->id());
        Address::create($request->all());
        return $this->response->created();
    }

    /**
     *地址详情
     */
    public function show(Address $address): \Dingo\Api\Http\Response
    {
        return $this->response->item($address,new AddressTransformer());
    }

    /**
     * 更新地址
     */
    public function update(AddressRequest $request,Address $address): \Dingo\Api\Http\Response
    {
        $address->update($request->all());
        return $this->response->noContent();
    }

    /**
     *删除地址
     */
    public function destroy(Address $address): \Dingo\Api\Http\Response
    {
        $address->delete();
        return $this->response->noContent();
    }

    /**
     * 是否设置为默认地址
     */
    public function default(Address $address): \Dingo\Api\Http\Response
    {
        if($address->is_default===1){
            $this->response->errorBadRequest('当前地址已经是默认地址,不能重复设置');
        }

        $address->is_default=1;
        $address->save();

        return $this->response->noContent();
    }
}
