<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem;

use Illuminate\Http\UploadedFile;
use League\Flysystem\Config;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface ChunkStorage
{
    public function uploadPart(string $uploadId, string $path, int $partNumber, UploadedFile $file, Config $config): string;

    public function initiateMultipartUpload(string $path, Config $config): string;

    public function completeMultipartUpload(string $uploadId, string $path, array $parts, Config $config): bool;

    public function abortMultipartUpload(string $uploadId, string $path, Config $config): bool;
}
