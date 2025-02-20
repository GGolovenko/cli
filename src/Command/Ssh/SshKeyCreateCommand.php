<?php

declare(strict_types = 1);

namespace Acquia\Cli\Command\Ssh;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SshKeyCreateCommand extends SshKeyCommandBase {

  /**
   * @var string
   * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
   */
  protected static $defaultName = 'ssh-key:create';

  protected function configure(): void {
    $this->setDescription('Create an SSH key on your local machine')
      ->addOption('filename', NULL, InputOption::VALUE_REQUIRED, 'The filename of the SSH key')
      ->addOption('password', NULL, InputOption::VALUE_REQUIRED, 'The password for the SSH key');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int {
    $filename = $this->determineFilename();
    $password = $this->determinePassword();
    $this->createSshKey($filename, $password);
    $output->writeln('<info>Created new SSH key.</info> ' . $this->publicSshKeyFilepath);

    return Command::SUCCESS;
  }

}
