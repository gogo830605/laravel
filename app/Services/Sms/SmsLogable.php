<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;
use Throwable;

trait SmsLogable
{
    protected $logClass = \App\Repositories\SmsLogRepository::class;

    protected $code = '';

    protected $type = 0;

    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function log()
    {
        try {
            $data = [
                'member_phone' => $this->reciever,
                'response'     => $this->feedback,
                'random'       => $this->code,
                'type'         => $this->type,
                'status'       => $this->status,
            ];

            return app($this->logClass)->create($data);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
