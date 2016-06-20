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
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use MageUtility\Log;

// Added for linking
use Symfony\Component\Console\Helper\QuestionHelper as QuestionHelper;
use Symfony\Component\Console\Helper\FormatterHelper as FormatterHelper;


class InstallMagentoCommand extends BaseCommand
{
    protected $_questionHelper = null;

    /**
     * Question helper
     *
     * @return QuestionHelper
     */
    protected function _questionHelper()
    {
        if ($this->_questionHelper === null) {
            $this->_questionHelper = $this->getHelper('question');
        }

        return $this->_questionHelper;
    }

    /**
     * Ask interactive question
     *
     * @param string $questionStr
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed|null $validator
     * @param int $maxAttempts
     * @return mixed
     */
    protected function _ask($questionStr, $input, $output, $validator = null, $maxAttempts = 3)
    {
        $question = new Question($questionStr);

        if ($validator !== null) {
            $question->setValidator($validator);
        }

        $question->setMaxAttempts($maxAttempts);

        // Interact and return value
        return $this->_questionHelper()->ask($input, $output, $question);
    }

    /**
     * Configures the current command
     */
    protected function configure()
    {
        $this->setName('install-magento')
            ->setDescription('Install Magento 2 in current directory.')
            ->setDefinition(array(
                new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Project name'),
                new InputOption('dir', null, InputOption::VALUE_OPTIONAL, 'Project directory')
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
        $whiteList = array('name', 'dir');
        $options = array_filter(array_intersect_key($input->getOptions(), array_flip($whiteList)));

        // Validate name
        $name = $this->_validateName($input, $output);
        $input->setOption('name', $name);

        // Validate dir
        $dir = $this->_validateDir($input, $output);
        $input->setOption('dir', $dir);

        $output->writeln('Complete');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');

        // Header
        $output->writeln(
            $formatter->formatBlock('Install Magento 2', 'bg=magenta;fg=white', true)
        );
    }

    /**
     * Validate name
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string $name
     */
    protected function _validateName(InputInterface $input, OutputInterface $output)
    {
        if (!$inputValue = $input->getOption('name')) {

            $inputValue = $this->_ask(
                'Project name: ',
                $input,
                $output,
                function ($value) use ($inputValue) {
                    if (null === $value) {
                        throw new \InvalidArgumentException(
                            'The project name can\'t be empty!'
                        );
                    }
                    if (!preg_match('/^[a-zA-Z0-9]+$/i', $value)) {
                        throw new \InvalidArgumentException(
                            'The project name ' . $value . ' is invalid, it should contain alphanumeric characters, no '
                            . 'spaces.'
                        );
                    }

                    return $value;
                }
            );

        } else {
            if (!preg_match('/^[a-zA-Z0-9]+$/i', $inputValue)) {
                throw new \InvalidArgumentException(
                    'The project name ' . $inputValue . ' is invalid, it should contain alphanumeric characters, no '
                    . 'spaces.'
                );
            }
        }

        return $inputValue;
    }

    /**
     * Validate directory
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string $inputValue
     */
    protected function _validateDir(InputInterface $input, OutputInterface $output)
    {
        if (!$inputValue = $input->getOption('dir')) {
            $inputValue = getcwd();
        } else {

            // If invalid directory
            if (!is_dir($inputValue)) {
                if ($input->isInteractive()) {

                    $output->writeln('<error>The directory specified [' . $inputValue . '] does not exist</error>');

                    $inputValue = $this->_ask(
                        'Project directory: ',
                        $input,
                        $output,
                        function ($value) use ($inputValue) {
                            if (!is_dir($value)) {
                                throw new \InvalidArgumentException(
                                    'The directory specified [' . $value . '] does not exist. Please ensure you enter the '
                                    . 'absolute directory path.'
                                );
                            }

                            return $value;
                        }
                    );
                } else {

                    // If not interactive, just throw exception
                    throw new \InvalidArgumentException(
                        'The directory specified [' . $inputValue . '] does not exist. Please ensure you enter the '
                        . 'absolute directory path.'
                    );
                }
            }
        }

        return $inputValue;
    }
}