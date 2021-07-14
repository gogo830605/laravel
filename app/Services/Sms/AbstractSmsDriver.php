<?php

namespace App\Services\Sms;

abstract class AbstractSmsDriver
{
    use SmsLogable;

    protected $config;

    protected $key;

    protected $secret;

    protected $url;

    protected $nationalCode;

    protected $currency;

    protected $feedback;

    protected $reciever;

    protected $body;

    protected $status;

    protected $enableLog = true;

    public function __construct($config)
    {
        $this->config = $config;
        $this->key = $config['key'];
        $this->secret = $config['secret'];
        $this->url = $config['url'];
        $this->nationalCode = $config['national_code'];
        $this->currency = $config['currency'];
    }

    public function to(string $reciever)
    {
        $this->reciever = $reciever;

        return $this;
    }

    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    public function getFeedBack()
    {
        return $this->feedback;
    }

    public function enableLog(bool $enableLog)
    {
        $this->enableLog = $enableLog;

        return $this;
    }

    public function send()
    {
        $this->status = $this->getStatus($this->feedback = $this->attempt());

        if ($this->enableLog) {
            $this->log();
        }

        return $this->status;
    }

    abstract public function attempt();

    abstract public function getStatus($feedback);
}
