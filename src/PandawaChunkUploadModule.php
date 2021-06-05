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
        foreach (['oss.public', 'oss.private'] as $driver) {
            if (config('filesystems.disk.' . $driver)) {
                Storage::extend($driver, function ($app, $config) {
                    $factory = new ChunkStorageFactory($app, new AliyunOssFactory(), config('chunk_upload.plugins'));

                    return $factory->create($config);
                });
            }
        }
    }
}
