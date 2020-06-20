#!/usr/bin/env php
<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit;
}

require 'vendor/autoload.php';

require __DIR__ . '/src/monkey.php';
