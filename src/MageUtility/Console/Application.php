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

namespace MageUtility\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use MageUtility\MageUtility;

class Application extends BaseApplication
{
    /**
     * ASCII Logo
     *
     * @var string
     */
    private static $logo = '
 __  __                  _   _ _   _ _ _ _
|  \/  | __ _  __ _  ___| | | | |_(_) (_) |_ _   _
| |\/| |/ _` |/ _` |/ _ \ | | | __| | | | __| | | |
| |  | | (_| | (_| |  __/ |_| | |_| | | | |_| |_| |
|_|  |_|\__,_|\__, |\___|\___/ \__|_|_|_|\__|\__, |
              |___/                          |___/
';

    /**
     * Application constructor
     */
    public function __construct()
    {
        parent::__construct('MageUtility', MageUtility::VERSION);
    }

    /**
     * Get help with logo
     *
     * @return string
     */
    public function getHelp()
    {
        return self::$logo . parent::getHelp();
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array
     */
    protected function getDefaultCommands()
    {
        $commands = array_merge(parent::getDefaultCommands(), array(
            new Command\InstallMagentoCommand()
        ));

        return $commands;
    }
}