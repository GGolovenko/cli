<?php

declare(strict_types = 1);

namespace Acquia\Cli\Tests\Commands\Auth;

use Acquia\Cli\Command\Auth\AuthLogoutCommand;
use Acquia\Cli\Config\CloudDataConfig;
use Acquia\Cli\DataStore\CloudDataStore;
use Acquia\Cli\Tests\CommandTestBase;
use Symfony\Component\Console\Command\Command;

/**
 * @property AuthLogoutCommandTest $command
 */
class AuthLogoutCommandTest extends CommandTestBase {

  protected function createCommand(): Command {
    return $this->injectCommand(AuthLogoutCommand::class);
  }

  /**
   * @return array<mixed>
   */
  public function providerTestAuthLogoutCommand(): array {
    return [
      [FALSE, []],
      [
        TRUE,
        // Are you sure you'd like to remove your Cloud API login credentials from this machine?
        ['y'],
      ],
    ];
  }

  /**
   * @dataProvider providerTestAuthLogoutCommand
   * @param array $inputs
   */
  public function testAuthLogoutCommand(bool $machineIsAuthenticated, array $inputs): void {
    if (!$machineIsAuthenticated) {
      $this->clientServiceProphecy->isMachineAuthenticated()->willReturn(FALSE);
      $this->removeMockCloudConfigFile();
    }

    $this->executeCommand([], $inputs);
    $output = $this->getDisplay();
    // Assert creds are removed locally.
    $this->assertFileExists($this->cloudConfigFilepath);
    $config = new CloudDataStore($this->localMachineHelper, new CloudDataConfig(), $this->cloudConfigFilepath);
    $this->assertFalse($config->exists('acli_key'));
  }

}
