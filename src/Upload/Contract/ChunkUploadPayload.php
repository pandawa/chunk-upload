<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Contract;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
final class ChunkUploadPayload
{
    public function __construct(
        public readonly string $identifier,
        public readonly int $part,
        public readonly int $totalChunks,
        public readonly string $fileName,
        public readonly int $chunkSize,
        public readonly int $totalSize,
    ) {}
}