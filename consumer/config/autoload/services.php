<?php
return [
    'consumers'=>[
        [
            'name'=>'CalculatorService',
            'service'=>\App\Rpc\CalculatorServiceInterface::class,
            'nodes' => [
                ['host' => '127.0.0.1', 'port' => 9502],
            ],
        ]
    ]
];