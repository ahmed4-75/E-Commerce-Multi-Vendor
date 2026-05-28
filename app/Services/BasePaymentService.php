<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BasePaymentService
{
    /**
     * Create a new class instance.
     */
    protected string $base_url = '';
    protected array $header = [];
    protected function buildRequest(string $method, string $url, $data = null)
    {
        $request = Http::withHeaders($this->header);
        return $request->send($method,$this->base_url . $url,['json' => $data]);
    }
}
