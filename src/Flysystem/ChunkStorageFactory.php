<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem;

use Illuminate\Contracts\Container\Container;
use League\Flysystem\Filesystem;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class ChunkStorageFactory
{
    private Container $container;
    private ChunkStorageFactoryAdapter $factoryAdapter;
    private array $plugins;

    public function __construct(
        Container $container,
        ChunkStorageFactoryAdapter $factoryAdapter,
        array $plugins = []
    ) {
        $this->container = $container;
        $this->factoryAdapter = $factoryAdapter;
        $this->plugins = $plugins;
    }

    public function create(array $config): Filesystem
    {
        $filesystem = new Filesystem($this->factoryAdapter->create($config));

        foreach ($this->plugins as $plugin) {
            $filesystem->addPlugin($this->container->make($plugin));
        }

        return $filesystem;
    }
}
