<?php
declare(strict_types=1);

namespace App\Rpc;

use Hyperf\RpcServer\Annotation\RpcService;

/**
 * @RpcService(name="CalculatorService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class CalculatorService implements CalculatorServiceInterface
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

    public function sub(int $a, int $b): int
    {
        return $a - $b;
    }

}