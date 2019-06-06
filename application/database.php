<?php

return [
    'type' => 'mysql',
    'hostname' => 'hn1.web.mysql.m,hn1.web.mysql.s',
    'database' => 'bingniao_cms2',
    'username' => 'bnweb_cms2',
    'password' => 'HOcUSJXHSjbln',
    'hostport' => '3306',
    'params' => [],
    'charset' => 'utf8mb4',
    'prefix' => 'oa_',
    'debug' => false,
    'deploy' => 1,              // 如果主从配置 则为1
    'rw_separate' => true,     // 数据库读写是否分离 主从式有效
    'master_num' => 1,          // 读写分离后 主服务器数量
    'slave_no' => '',           // 指定从服务器序号
    'fields_strict' => false,           // 是否严格检查字段是否存在
    'resultset_type' => 'array',        // 数据集返回类型
    'auto_timestamp' => false,          // 自动写入时间戳字段
    'datetime_format' => 'Y-m-d H:i:s', // 时间字段取出后的默认时间格式
    'sql_explain' => false,             // 是否需要进行SQL性能分析
];
