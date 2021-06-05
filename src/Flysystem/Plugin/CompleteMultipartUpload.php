<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem\Plugin;

use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;
use Pandawa\ChunkUpload\Flysystem\SupportsChunkUpload;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class CompleteMultipartUpload extends AbstractPlugin
{
    use SupportsChunkUpload;

    public function getMethod(): string
    {
        return 'completeMultipartUpload';
    }

    public function handle(string $uploadId, string $path, array $parts, array $config = [])
    {
        if (!$this->supportsChunkUpload()) {
            return false;
        }

        $chunkParts = [];
        $count = 0;

        foreach ($parts as $part) {
            $chunkParts[] = [
                'PartNumber' => ($count + 1),
                'ETag'       => $part,
            ];

            $count += 1;
        }

        return $this->filesystem->getAdapter()->completeMultipartUpload(
            $uploadId,
            $path,
            $chunkParts,
            new Config($config)
        );
    }
}
