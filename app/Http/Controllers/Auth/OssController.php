<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OssController extends BaseController
{
    /**
     * 生成oss上传token
     * @throws \JsonException
     */
    public function token(): \Dingo\Api\Http\Response
    {
        $disk = Storage::disk('oss');
        //获取
        $config = $disk->signatureConfig($prefix = '/', $callBackUrl = '', $customData = [], $expire = 300);
        $configArr= json_decode($config, true, 512, JSON_THROW_ON_ERROR);
        return $this->response->array($configArr);
    }
}
