<?php

namespace App\Services\Sms;

use App\Constants\Sms;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Exception;

class SmsManager
{
    /**
     * 設定檔
     *
     * @var array
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 依幣別取得對應簡訊服務
     *
     * @param string $currency
     * @return AbstractSmsDriver|null
     */
    public function currency(string $currency)
    {
//        $name = $this->getDriverName($currency);
//        $config = $this->getDriverConfig($name);
//        $config['national_code'] = $this->getNationalCode($currency);
//        $config['currency'] = $currency;

        return $currency;
    }

    /**
     * 解析 driver 實例
     *
     * @param array $config
     * @return AbstractSmsDriver
     */
    protected function driver(array $config)
    {
        $driver = __NAMESPACE__ . '\\' . Str::studly($config['name']) . 'Driver';

        if (class_exists($driver)) {
            return new $driver($config);
        }

        throw new Exception("$driver not found!");
    }

    /**
     * 取得 driver 名稱
     *
     * @param string $currency
     * @return string
     */
    protected function getDriverName(string $currency): string
    {
        if (empty($name = Arr::get($this->config, "default.$currency"))) {
            throw new Exception("Could not find a driver that supports $currency");
        }

        return $name;
    }

    /**
     * 取得 driver config
     *
     * @param string $name
     * @return array
     */
    protected function getDriverConfig(string $name): array
    {
        if (empty($config = Arr::get($this->config, "vendors.$name"))) {
            throw new Exception("Config named $name has not been set!");
        }

        return $config;
    }

    /**
     * 取得國碼
     *
     * @param string $currency
     * @return string
     */
    protected function getNationalCode(string $currency): string
    {
        return Arr::get(Sms::NATIONAL_CODE, $currency, '');
    }
}
