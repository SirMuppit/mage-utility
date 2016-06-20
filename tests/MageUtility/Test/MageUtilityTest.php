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

use MageUtility\Console\Command\InstallMagentoCommand;
use Symfony\Component\Console\Application as BaseApp;
use Symfony\Component\Console\Tester\CommandTester;

class MageUtilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|BaseApp
     */
    private $_app = null;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->_app = new BaseApp();
    }

    /**
     * Tear down and cleanup
     */
    public function tearDown()
    {
        $this->_app = null;
    }

    /**
     * Test install Magento execute returns complete
     */
    public function testInstallMagentoExecuteComplete()
    {
        $this->_app->add(new InstallMagentoCommand());

        $command = $this->_app->find('install-magento');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'   => $command->getName(),
            '--name'    => 'TestProject'
        ));

        $this->assertRegexp('/Complete/', $commandTester->getDisplay());
        //$this->assertRegExp('/.../', $commandTester->getDisplay());
    }

    /**
     * Test for invalid character in option "name"
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCharInOptionName()
    {
        $this->_app->add(new InstallMagentoCommand());

        $command = $this->_app->find('install-magento');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'   => $command->getName(),
            '--name'    => '!'
        ));
    }

    /**
     * Test for invalid space character in option "name"
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidSpaceInOptionName()
    {
        $this->_app->add(new InstallMagentoCommand());

        $command = $this->_app->find('install-magento');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'   => $command->getName(),
            '--name'    => 'Test Test'
        ));
    }
}