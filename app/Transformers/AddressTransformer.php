<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Address;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{


    /**
     * @throws \Exception
     */
    #[ArrayShape(['id' => "int", 'name' => "string", 'city_id' => "int", 'city_name' => "mixed", 'address' => "string", 'phone' => "string", 'created_at' => "\Illuminate\Support\Carbon|null", 'updated_at' => "\Illuminate\Support\Carbon|null"])]
    public function transform(Address $address): array
    {
        return [
            'id'=>$address->id,
            'name'=>$address->name,
            'city_id'=>$address->city_id,
            'city_name'=>city_name($address->city_id),
            'address'=>$address->address,
            'phone'=>$address->phone,
            'created_at'=>$address->created_at->toDateTimeString(),
            'updated_at'=>$address->updated_at->toDateTimeString()
        ];
    }
}
