<?php
/**
 * Mage Utility
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License (MIT)
 * that is bundled with this package in the file LICENSE_MIT.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @copyright   Copyright (c) 2016 Shaughn Le Grange - Hatlen (http://shaughn.pro)
 * @license     https://opensource.org/licenses/MIT  MIT License (MIT)
 */

if (PHP_SAPI !== 'cli') {
    echo 'Warning: MageUtility should be invoked via the CLI version of PHP, not the '.PHP_SAPI.' SAPI'.PHP_EOL;
}

require __DIR__.'/../src/bootstrap.php';

use MageUtility\Console\Application;

error_reporting(-1);

if (function_exists('ini_set')) {
    @ini_set('display_errors', 1);

    $memoryInBytes = function ($value) {
        $unit = strtolower(substr($value, -1, 1));
        $value = (int) $value;
        switch($unit) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'g':
                $value *= 1024;
                // No break (cumulative multiplier) - suppress inspection
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'm':
                $value *= 1024;
                // No break (cumulative multiplier) - suppress inspection
            case 'k':
                $value *= 1024;
        }

        return $value;
    };

    $memoryLimit = trim(ini_get('memory_limit'));

    // Increase memory_limit if it is lower than 1GB
    if ($memoryLimit != -1 && $memoryInBytes($memoryLimit) < 1024 * 1024 * 1024) {
        @ini_set('memory_limit', '1G');
    }

    unset($memoryInBytes, $memoryLimit);
}

// Run app
$application = new Application();
$application->run();