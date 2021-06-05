<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem\Plugin;

use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;
use Pandawa\ChunkUpload\Flysystem\SupportsChunkUpload;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AbortMultipartUpload extends AbstractPlugin
{
    use SupportsChunkUpload;

    public function getMethod(): string
    {
        return 'initiateMultipartUpload';
    }

    public function handle(string $uploadId, string $path, array $config = [])
    {
        if (!$this->supportsChunkUpload()) {
            return false;
        }

        return $this->filesystem->getAdapter()->abortMultipartUpload(
            $uploadId,
            $path,
            new Config($config)
        );
    }
}
