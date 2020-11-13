<?php
return [
    ['commands\\AppCommand',\helper\Console::HAS_COMMAND],
    ['commands\\app\\CreateCommand',\helper\Console::HAS_COMMAND],
    ['commands\\app\\PackCommand',\helper\Console::HAS_COMMAND],
    ['commands\\DemoCommand',\helper\Console::HAS_COMMAND],
    ['commands\\TestCommand',\helper\Console::HAS_COMMAND],
    ['commands\\HomeCommand',\helper\Console::HAS_GROUP],
];
