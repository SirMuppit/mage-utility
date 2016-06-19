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
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
// Added for linking
use Symfony\Component\Console\Helper\QuestionHelper as QuestionHelper;
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
The <info>install-magento</info> will install Magento 2 in the current directory.

<info>php mageutility.phar install-magento</info>

EOT
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $whiteList = array('name');
        $options = array_filter(array_intersect_key($input->getOptions(), array_flip($whiteList)));

        new Log($options);

        //$output->writeln($options['name']);

        if ($input->isInteractive()) {
            $question = new ConfirmationQuestion('Continue installing in the current directory?', false);

            // Commented this as i need to include this in test
            /*if ($helper->ask($input, $output, $question)) {
                return;
            }*/
        }

        $output->writeln('Complete');

    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        // Validate project name interactive
        if (!$name = $input->getOption('name')) {

            // Set validation function
            $validator = function ($value) use ($name) {
                if (null === $value) {
                    throw new \InvalidArgumentException(
                        'The project name can\'t be empty!'
                    );
                }
                if (!preg_match('/^[a-zA-Z0-9]+$/i', $value)) {
                    throw new \InvalidArgumentException(
                        'The project name ' . $value . ' is invalid, it should contain alphanumeric characters, no spaces.'
                    );
                }

                return $value;
            };

            // Set interactive question
            $question = new Question('Project Name: ');
            $question->setValidator($validator)
                ->setMaxAttempts(3);

            // Interact and return value
            $name = $helper->ask($input, $output, $question);

        } else {
            if (!preg_match('/^[a-zA-Z0-9]+$/i', $name)) {
                throw new \InvalidArgumentException(
                    'The project name ' . $name . ' is invalid, it should contain alphanumeric characters, no spaces.'
                );
            }
        }

        $input->setOption('name', $name);
    }
}