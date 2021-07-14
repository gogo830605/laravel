<?php

namespace App\Services\Sms;

use App\Commons\CurlAdapter;
use App\Constants\Sms;
use Carbon\CarbonTimeZone;

class ImfsDriver extends AbstractSmsDriver
{
    use CurlAdapter;

    public function attempt()
    {
        $tz = CarbonTimeZone::create('+08:00');
        $datetime = now($tz)->format('YmdHis');
        $sign = md5("{$this->key}{$this->secret}{$datetime}");

        $strOnlineSend = "http://sms.skylinelabs.cc:20003/sendsmsV2?";
        $strOnlineSend .= "account={$this->key}";
        $strOnlineSend .= "&sign={$sign}";
        $strOnlineSend .= "&datetime={$datetime}";
        $strOnlineSend .= "&numbers={$this->reciever}";
        $strOnlineSend .= "&content=" . urlencode($this->body);

        return json_decode(($this->get($strOnlineSend))->getBody()->getContents());
    }

    public function getStatus($feedback)
    {
        return ((data_get($feedback, 'status') == 0) && (data_get($feedback, 'success') > 0)) ? Sms::SUCCESS : Sms::FAILED;
    }
}
