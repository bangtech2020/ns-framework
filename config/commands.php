<?php
return [
    ['commands\\AppCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\CreateCommand',\helper\Console::HAS_COMMAND],
    ['commands\\App\\PackCommand',\helper\Console::HAS_COMMAND],
    ['commands\\ServerCommand',\helper\Console::HAS_COMMAND],
    ['commands\\Server\\InternetCommand',\helper\Console::HAS_COMMAND],
    ['commands\\DemoCommand',\helper\Console::HAS_COMMAND],
    ['commands\\TestCommand',\helper\Console::HAS_COMMAND],
    ['commands\\HomeCommand',\helper\Console::HAS_GROUP],
];
