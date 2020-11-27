<?php
return [
    'app_start'=>[
        \eventListener\AppStartEventListener::class
    ],
    'app_stop'=>[
        \eventListener\AppStopEventListener::class
    ]
];
