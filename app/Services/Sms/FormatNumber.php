<?php

namespace App\Services\Sms;

use Illuminate\Support\Str;

trait FormatNumber
{
    /**
     * 需要格式化的幣別
     *
     * @var array
     */
    protected $shouldFormatCurrencies = ['VND'];

    /**
     * 手機號碼格式
     *
     * @param string $number
     * @return string
     */
    public function format(string $number): string
    {
        if (in_array($this->currency, $this->shouldFormatCurrencies) && Str::startsWith($number, '0')) {
            return substr($number, 1);
        }

        return $number;
    }
}
