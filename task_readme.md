# 结合thinkphp5的异步任务服务框架

## 使用介绍

### 配置

在`application/config.php`中配置：

```php
[
    'ibn_task_workers' => [
        //一组worker配置
        'default' => [
            'type' => 'redis', //worker的类型
            'num' => 2, //worker进程数
            'ibn_redis_key' => 'base', //采用ibn_redis的哪个key
            'polling_timeout' => 5, //redis blpop的超时时间
            'queues' => [ //负责读取的任务队列
                'default'
            ]
        ]
    ],
    'ibn_task_queues' => [
        //任务队列定义
        'default' => [
            'type' => 'redis', //任务队列类型，必须与worker类型一致
            'ibn_redis_key' => 'base', //采用ibn_redis的哪个key
            'delay_task_concurrency' => 4, //延迟任务放入几个zset(ibntaskz_{queue-key}_{index}),
        ]
    ]
]
```

### 启动异步任务服务

启动

```bash
php think task:server --worker=default start
```
注意多台机器可以同时启动相同的worker

停止

```bash
php think task:server --worker=default stop
```

重启

```bash
php think task:server --worker=default restart
```

查看日志

```bash
tail -f runtime/log/ibntask_default_20170814.log
```

### 编写task处理器(TaskCommand)

参见：[Index.php](./application/index/task/Index.php)

框架会自动重载thinkphp5的配置文件:

- application/config.php以及各个module下的config.php会每隔30秒重新加载一次
- database/cache/ibn_redis这3个key只会读取application/config.php中的值，各个module的config.php无法覆盖
- database/cache/ibn_redis如果配置发生变化，会自动关闭对应的实例，后续TaskCommand使用时会自动重新创建
- database如果十分钟内未查询，则会自动关闭对应的实例
- 配置重新加载时，会调用`Hook::listen('ibntask_config_reload');`和`Hook::listen('ibntask_config_reload:{module}');`，用户可以自己根据需要来进行相应的处理

### 提交任务

```php
//提交延迟任务，5秒后执行
TaskClientPool::get('default')->submitDelay(
    5, 
    'index/Index/index', 
    [
        1, [time(), 1]
    ]
);
TaskClientPool::get('default')->submit(
    'index/Index/index', 
    [
        9, [time(), 9]
    ]
);
```

任务重试说明：

- submitDelay/submit方法的`$retryNum`/`$retryPeriod`控制重试次数以及每次重试的时间间隔
- $retryNum默认是不进行重试
- $retryPeriod未指定，则默认是10秒
- task处理器(TaskCommand)throw非`TaskNoRetryException`异常，则会进入重试流程

### 命令行直接运行task

```bash
php think task:run index/Index/index help
php think task:run index/Index/index 10 a,b,c
```