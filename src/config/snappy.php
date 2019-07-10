<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => 'xvfb-run --auto-servernum --server-num=1 '.base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
        'timeout' => false,
        'options' => [],
        'env' => [],
    ],
    'image' => [
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => [],
        'env' => [],
    ],
];
