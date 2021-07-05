<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload;

use Illuminate\Support\Facades\Storage;
use Pandawa\ChunkUpload\Flysystem\Adapter\AliyunOssFactory;
use Pandawa\ChunkUpload\Flysystem\ChunkStorageFactory;
use Pandawa\Component\Module\AbstractModule;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class PandawaChunkUploadModule extends AbstractModule
{
    protected function build(): void
    {
        Storage::extend('oss', function ($app, $config) {
            $factory = new ChunkStorageFactory(
                $app,
                new AliyunOssFactory(),
                config('chunk_upload.plugins')
            );

            return $factory->create($config);
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/chunk_upload.php' => config_path('chunk_upload.php'),
            ], 'config');
        }
    }

    protected function init(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/chunk_upload.php', 'chunk_upload');
    }
}
