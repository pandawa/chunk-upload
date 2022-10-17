<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload;

use Illuminate\Support\Facades\Storage;
use Pandawa\Bundle\DependencyInjectionBundle\Plugin\ImportServicesPlugin;
use Pandawa\Bundle\FoundationBundle\Plugin\ImportConfigurationPlugin;
use Pandawa\ChunkUpload\Filesystem\ChunkStorageMacroFactoryInterface;
use Pandawa\Component\Foundation\Bundle\Bundle;
use Pandawa\Contracts\Foundation\HasPluginInterface;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class ChunkUploadBundle extends Bundle implements HasPluginInterface
{
    public function plugins(): array
    {
        return [
            new ImportConfigurationPlugin(basePath: '../config', configFilename: 'chunk_upload'),
            new ImportServicesPlugin(),
        ];
    }

    public function boot(): void
    {
        $factories = $this->getConfig('storage_factories');

        foreach ($factories as $disk => $factoryClass) {
            /** @var ChunkStorageMacroFactoryInterface $factory */
            $factory = $this->app->make($factoryClass);

            Storage::disk($disk)->macro('uploadPart', $factory->uploadPart());
            Storage::disk($disk)->macro('abortMultipartUpload', $factory->abortMultipartUpload());
            Storage::disk($disk)->macro('completeMultipartUpload', $factory->completeMultipartUpload());
            Storage::disk($disk)->macro('initiateMultipartUpload', $factory->initiateMultipartUpload());
        }
    }
}
