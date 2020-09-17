<?php

namespace App\Rpc;


use Hyperf\RpcClient\AbstractServiceClient;

class CalculatorService extends AbstractServiceClient implements CalculatorServiceInterface
{
    protected $serviceName = 'CalculatorService';
    protected $protocol = 'jsonrpc-http';

    public function add(int $a, int $b): int
    {
        return $this->__request(__FUNCTION__, compact('a', 'b'));
    }

    public function sub(int $a, int $b): int
    {
        return $this->__request(__FUNCTION__, compact('a', 'b'));
    }
}