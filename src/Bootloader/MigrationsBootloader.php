<?php

declare(strict_types=1);

namespace Spiral\Cycle\Bootloader;

use Cycle\Migrations\Config\MigrationConfig;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\Migrator;
use Cycle\Migrations\RepositoryInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;

final class MigrationsBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        TokenizerBootloader::class,
        DatabaseBootloader::class,
    ];

    protected const SINGLETONS = [
        Migrator::class => Migrator::class,
        RepositoryInterface::class => FileRepository::class,
    ];

    public function init(
        ConfiguratorInterface $config,
        EnvironmentInterface $env,
        DirectoriesInterface $dirs
    ): void {
        if (! $dirs->has('migrations')) {
            $dirs->set('migrations', $dirs->get('app') . 'migrations');
        }

        $config->setDefaults(
            MigrationConfig::CONFIG,
            [
                'directory' => $dirs->get('migrations'),
                'table' => 'migrations',
                'safe' => $env->get('SAFE_MIGRATIONS', false),
            ]
        );
    }
}
