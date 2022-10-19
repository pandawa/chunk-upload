<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Contract;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Chunked
{
    public function __construct(
        public readonly File $file,
        public readonly int $part,
        public readonly ?string $reference = null,
        public readonly ?int $totalChunks = null,
        public readonly bool $newChunk = false
    ) {}
}
