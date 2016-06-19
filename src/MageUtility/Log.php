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

namespace MageUtility;

use Symfony\Component\Console\Output\StreamOutput;

class Log
{
    /**
     * Log constructor
     *
     * @param mixed $mixed
     */
    public function __construct($mixed)
    {
        $logfile = 'output.log';
        $logDir  = __DIR__ . DS . '..' . DS . '..' . DS . 'var' . DS . 'log';
        $logFile = $logDir . DS . $logfile;

        // Create log directory if not exists
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0750, true);
            //@chmod($logDir, 0750);
        }

        // Create log file if not exists
        if (!file_exists($logFile)) {
            @file_put_contents($logFile, '');
            @chmod($logFile, 0640);
        }

        $file = @fopen($logFile, 'a+', false);

        if ($file) {
            $output = new StreamOutput($file);

            if (is_array($mixed) || is_object($mixed)) {
                $mixed = print_r($mixed, true);
            }

            $output->write($mixed, true, $output::OUTPUT_RAW);
        }
    }
}
