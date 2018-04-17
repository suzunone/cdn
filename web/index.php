<?php

require __DIR__ . '/../vendor/autoload.php';

try {
    $LocalHostSetting = new Suzunone\CDN\Config\Host();
    $LocalHostSetting->setHttpPort('8080');
    $LocalHostSetting->setRequestTimeOut(3);

    $main = \Suzunone\CDN\Bootstrap::main();
    $main
        ->setRootPath('/index.php')
        ->setAllowHostName('php.net')
        ->setAllowHostName('localhost', $LocalHostSetting)
        ->execute();
} catch (\Exception $exception) {
    header('HTTP/1.1 404 Not Found');
}
