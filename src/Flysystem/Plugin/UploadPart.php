<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem\Plugin;

use Illuminate\Http\UploadedFile;
use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;
use Pandawa\ChunkUpload\Flysystem\SupportsChunkUpload;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class UploadPart extends AbstractPlugin
{
    use SupportsChunkUpload;

    public function getMethod(): string
    {
        return 'uploadPart';
    }

    public function handle(string $uploadId, string $path, int $partNumber, UploadedFile $file, array $config = [])
    {
        if (!$this->supportsChunkUpload()) {
            return false;
        }

        $chunkReferenceId = $this->filesystem->getAdapter()->uploadPart(
            $uploadId,
            $path,
            $partNumber,
            $file,
            new Config($config)
        );

        if ('"' === substr($chunkReferenceId, 0, 1)
            && '"' === substr($chunkReferenceId, strlen($chunkReferenceId) - 1, 1)) {
            $chunkReferenceId = substr($chunkReferenceId, 1, strlen($chunkReferenceId) - 2);
        }

        return $chunkReferenceId;
    }
}
