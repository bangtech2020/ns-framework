<?php
return [
    //APP 管理模块
    ['commands\\Composer',\helper\Console::HAS_COMMAND],
    ['commands\\AppCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\WebServiceCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\CreateCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\PackCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\InstallCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\UninstallCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\LoadCommand',\helper\Console::HAS_COMMAND],

    //服务模块
    //['commands\\ServerCommand',\helper\Console::HAS_COMMAND],
    //['commands\\Server\\InternetCommand',\helper\Console::HAS_COMMAND],
];
