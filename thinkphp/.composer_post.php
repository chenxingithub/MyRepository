<?php
/**
 * composer create project/update/install后调用的脚本
 * @author: dryangkun
 * @date: 2017/8/18 下午2:40
 */

$dir = __DIR__ . '/.git';
if (is_dir($dir)) {
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dir);
}
unlink(__FILE__);