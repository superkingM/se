- JSON RPC 是一种基于 JSON 格式的轻量级的 RPC 协议标准
# 安装
- 安装RPC相关拓展包
>composer require hyperf/json-rpc
composer require hyperf/rpc-server
composer require hyperf/rpc-client

# 定义服务提供者
- 这里我们新建项目名改为`provider`，用来提供服务
- 新建文件`App\Rpc\CalculatorService.php`，注册为`jsonrpc-http`服务

```php
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
```
- 打开config/autoload/server.php修改为rpc服务

```php
<?php
declare(strict_types=1);
use Hyperf\Server\Server;
use Hyperf\Server\SwooleEvent;

return [
    'mode' => SWOOLE_PROCESS,
    'servers' => [
        [
            'name' => 'jsonrpc-http',
            'type' => Server::SERVER_HTTP,
            'host' => '0.0.0.0',
            'port' => 9502,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                SwooleEvent::ON_REQUEST => [\Hyperf\JsonRpc\HttpServer::class, 'onRequest'],
            ],
        ],
    ],
    'settings' => [
        'enable_coroutine' => true,
        'worker_num' => swoole_cpu_num(),
        'pid_file' => BASE_PATH . '/runtime/hyperf.pid',
        'open_tcp_nodelay' => true,
        'max_coroutine' => 100000,
        'open_http2_protocol' => true,
        'max_request' => 100000,
        'socket_buffer_size' => 2 * 1024 * 1024,
        'buffer_output_size' => 2 * 1024 * 1024,
    ],
    'callbacks' => [
        SwooleEvent::ON_WORKER_START => [Hyperf\Framework\Bootstrap\WorkerStartCallback::class, 'onWorkerStart'],
        SwooleEvent::ON_PIPE_MESSAGE => [Hyperf\Framework\Bootstrap\PipeMessageCallback::class, 'onPipeMessage'],
        SwooleEvent::ON_WORKER_EXIT => [Hyperf\Framework\Bootstrap\WorkerExitCallback::class, 'onWorkerExit'],
    ],
];
- 
```
# 服务消费者
- 新建项目修改项目名为consumer，用来消费服务
- 新建文件`App\Rpc\CalculatorService.php`

```php
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
```
- 新建config/autoload/services.php，用于配置服务

```php
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
```
# 服务消费者调用
- 打开默认index控制器，在index方法中进行消费

```php
<?php
declare(strict_types=1);
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
```

- 启动服务提供者和消费者项目，访问消费者首页，进行服务调用
