<?php

$router = $di->getRouter();

$router->add(
    '/thuong-hieu-.+?(\d+$)',[
        'controller' => 'brand',
        'action' => 'index',
        'id' => 1,
    ]
);

$router->handle();
