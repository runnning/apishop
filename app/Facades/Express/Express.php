<?php

namespace App\Facades\Express;

use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Express
{
     //商户ID
     protected string $EBusinessID;
    //API KEY
     protected string $ApiKey;
    //模式
    protected string $mode;

    public function __construct(){
        $this->EBusinessID=config('express.EBusinessID');
        $this->ApiKey=config('express.ApiKey');
        $this->mode=config('express.mode');
    }

    /**
     * 即时查询-快递足迹
     * @throws \JsonException
     */
    public function track(string $CustomerName='',string $OrderCode='',?string $ShipperCode='',?string $LogisticCode=''): array
    {
        //准备请求参数
        $requestData= "{".
            "'CustomerName': '{$CustomerName}',".
            "'OrderCode': '{$OrderCode}',".
            "'ShipperCode': '{$ShipperCode}',".
            "'LogisticCode': '{$LogisticCode}',".
            "}";
        //发送请求
        $result=Http::asForm()->post(
            $this->url('track'),
            $this->formRequestData($requestData,1002)
        );
        return $this->formatResData($result);
    }

    /**
     * 格式化请求参数
    */
     #[Pure] #[ArrayShape(['EBusinessID' => "string", 'RequestType' => "string", 'RequestData' => "string", 'DataType' => "string", 'DataSign' => "string"])]
    protected function formRequestData(string $requestData,string $requestType): array
    {
        $data = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => $requestType, //免费即时查询接口指令1002/在途监控即时查询接口指令8001/地图版即时查询接口指令8003
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $data['DataSign'] = $this->encrypt($requestData);
        return $data;
    }

    /**
     * 格式化响应参数
     * @throws \JsonException
     */
    protected function formatResData(string $result)
    {
        $result= json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        //Api服务器请求报错
        if($result['Success']===false){
            return $result['ResponseData'];
        }

        $ResponseData= json_decode($result['ResponseData'], true, 512, JSON_THROW_ON_ERROR);

        //请求成功,但是未请求到数据,请求的参数有问题
        if($ResponseData){
            return $ResponseData['Reason'];
        }
        return  $ResponseData;
    }

    /**
     * 返回Api url
    */
    protected function url(string $type): string
    {
        $url=[
            'track'=>[
                'product'=>'https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx',
                'dev'=>'https://www.kdniao.com/UserCenter/v2/SandBox/SandboxHandler.ashx?action=CommonExcuteInterface'
            ]
        ];
        return $url[$type][$this->mode];
    }

    /**
     * 电商Sign签名生成
     * @param string $data 内容
     * @return string
     */
    public function encrypt(string $data): string
    {
        return urlencode(base64_encode(md5($data.$this->ApiKey)));
    }
}
