#!/bin/sh

dir=$(cd "$(dirname "$0")"; pwd)
cd $dir

IBINGNIAO_TECH_ENV=$1
if [ -n "$IBINGNIAO_TECH_ENV" ];then
    export IBINGNIAO_TECH_ENV=$IBINGNIAO_TECH_ENV
else
    export IBINGNIAO_TECH_ENV=''
fi

if [ -f "runtime/classmap.php" ];then
    rm -f runtime/classmap.php
fi
if [ -f "runtime/init.php" ];then
    rm -f runtime/init.php
fi
if [ -f "runtime/route.php" ];then
    rm -f runtime/route.php
fi

php think optimize:autoload
php think optimize:config
php think optimize:route
#php think optimize:schema

