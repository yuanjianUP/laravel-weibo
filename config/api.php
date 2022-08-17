<?php
return [
    'rate_limits' => [
        //访问频率限制，次数/分钟
        'access' => env('RATE_LIMITS', '60,1'),
        'sign' => env('SIGN_RATE_LIMITS','10,1')
    ]
];
