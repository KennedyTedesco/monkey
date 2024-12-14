#!/usr/bin/env php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Monkey\Monkey;

if (PHP_SAPI !== 'cli') {
    exit;
}

if (!isset($GLOBALS['argv']) || !is_array($GLOBALS['argv'])) {
    exit(1);
}

$cli = new Monkey();
exit($cli->run($GLOBALS['argv']));
