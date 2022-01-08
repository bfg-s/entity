<?php

namespace Bfg\Entity;

use Bfg\Installer\Providers\InstalledProvider;

/**
 * Class ServiceProvider.
 * @package Bfg\Entity
 */
class ServiceProvider extends InstalledProvider
{
    /**
     * Set as installed by default.
     * @var bool
     */
    public bool $installed = true;

    /**
     * Executed when the provider is registered
     * and the extension is installed.
     * @return void
     */
    public function installed(): void
    {
        // TODO: Implement installed() method.
    }

    /**
     * Executed when the provider run method
     * "boot" and the extension is installed.
     * @return void
     */
    public function run(): void
    {
        // TODO: Implement run() method.
    }
}
