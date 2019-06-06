#!/bin/sh

#参数1：需要切换的配置名称，如mysql redis
#参数2：1/2 1表示切换配置 2表示恢复原先配置
#参数3：环境变量 如dev,prod_hn1

dir=$(cd "$(dirname "$0")"; pwd)
cd $dir

name=$1
action=$2
env=$3

if [ ! -n "$1" ];then
    echo "Usage $0 {name} {action}(1表示切换;2表示重置;) {app_status}"
    exit
fi
if [ ! -n "$2" ];then
    echo "Usage $0 {name} {action}(1表示切换;2表示重置;) {app_status}"
    exit
fi

if [ -n "$env" ];then
    export IBINGNIAO_TECH_ENV=$env
else
    export IBINGNIAO_TECH_ENV=''
    env="config"
fi

filenames=("${env}_${name}.php" "config_${name}.php")
if [ "$action" = "1" ];then
    if [ ! -d runtime/config_switch ];then
    mkdir -p runtime/config_switch && chmod 775 runtime/config_switch
    if [ $? -ne 0 ];then
        echo "创建runtime/config_switch失败"
        exit 1
    fi
    fi

    exists=0
    for i in "${filenames[@]}"
    do
    if [ -f "application/config_switch/${i}" ];then
        echo y | cp "application/config_switch/${i}" runtime/config_switch/
        if [ $? -ne 0 ];then
            echo "copy application/config_switch/${i}失败"
            exit 1
        fi
        exists=1
        break
    fi
    done
    if [ $exists -eq 0 ];then
        echo "application/config_switch下对应${name}的配置文件不存在"
        exit 1
    fi
elif [ "${action}" = "2" ];then
    exists=0
    for i in "${filenames[@]}"
    do
        if [ -f "runtime/config_switch/${i}" ];then
            exists=1
            rm -f "runtime/config_switch/${i}"
            if [ $? -ne 0 ];then
                echo "rm runtime/config_switch/${i}失败"
                exit 1
            fi
        fi
    done
    if [ $exists -eq 0 ];then
        echo "runtime/config_switch下对应${name}的配置文件不存在"
        exit 0
    fi
else
    echo "action = 1 or 2; 1表示切换配置 2表示恢复原先配置"
    exit
fi

if [ -f "runtime/init.php" ];then
    rm -f runtime/init.php
fi
php think optimize:config