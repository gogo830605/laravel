<?php

namespace App\Services\Sms;

use App\Commons\CurlAdapter;
use App\Constants\Sms;
use Carbon\CarbonTimeZone;

class CloudTechnologyDriver extends AbstractSmsDriver
{
    use CurlAdapter;

    public function attempt()
    {
        $tz = CarbonTimeZone::create('+08:00');
        $datetime = now($tz)->format('YmdHis');
        $sign = md5("{$this->key}{$this->secret}{$datetime}");

        $url = "http://yxc.sms.zuiniu.xin:9090/sms/batch/v1";
        $param = [
            "sign" => $sign,
            "timestamp" => $datetime,
            "phone" => $this->reciever,
//            "extend" => "123",
            "appcode" => $this->code,
            "appkey" => $this->key,
            "msg" => urlencode($this->body)
        ];

        return json_decode(($this->post($url, $param))->getBody()->getContents());
    }

    public function getStatus($feedback)
    {
        foreach (data_get($feedback, 'result') as $val) {
            if(data_get($val, 'status') != '00000') return Sms::FAILED;
        }
        return Sms::SUCCESS;
    }
}
