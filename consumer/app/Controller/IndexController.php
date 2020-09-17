<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;


use App\Rpc\CalculatorService;

class IndexController extends AbstractController
{

    public function index(CalculatorService $service)
    {
        $add =  $service->add(2,3);
        $sub = $service->sub(15,3);

        return [
            'add'=>$add,
            'sub'=>$sub
        ];

    }
}
