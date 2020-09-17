<?php


namespace App\Rpc;

interface CalculatorServiceInterface
{
    public function add(int $a, int $b): int;

    public function sub(int $a, int $b): int;
}
