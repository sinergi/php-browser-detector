<?php

require_once __DIR__ . '/ChangeWindows.php';
require_once __DIR__ . '/Wikipedia.php';

Wikipedia::fetch();
ChangeWindows::fetchVersions();
