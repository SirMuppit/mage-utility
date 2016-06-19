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

namespace MageUtility\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use MageUtility\Log;

class InstallMagentoCommand extends BaseCommand
{
    /**
     * Configures the current command
     */
    protected function configure()
    {
        $this->setName('install-magento')
            ->setDescription('Install Magento 2 in current directory.')
            ->setDefinition(array(
                new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Project name')
            ))
            ->setHelp(<<<EOT
The <info>install-magento</info> will install Magento 2 in the current
directory.

<info>php mageutility.phar install-magento</info>

EOT
            );
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $whiteList = array('name');
        $options = array_filter(array_intersect_key($input->getOptions(), array_flip($whiteList)));

        //new Log($input->getOptions());
        new Log(array_intersect_key($input->getOptions(), array_flip($whiteList)));
        //$output->writeln(sprintf('Received data: %s', $options));

        //new ConfirmationQuestion('Continue installing in the current directory?', true)
    }
}