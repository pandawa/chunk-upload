<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\DTO;

use Pandawa\ChunkUpload\Upload\Contract\File;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Chunked
{
    protected File $file;
    protected int $part;
    protected ?string $reference;
    protected ?int $totalChunks;
    private bool $newChunk;

    public function __construct(File $file, int $part, ?string $reference = null, ?int $totalChunks = null, bool $newChunk = false)
    {
        $this->file = $file;
        $this->part = $part;
        $this->reference = $reference;
        $this->totalChunks = $totalChunks;
        $this->newChunk = $newChunk;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function getPart(): int
    {
        return $this->part;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getTotalChunks(): ?int
    {
        return $this->totalChunks;
    }

    public function isNewChunk(): bool
    {
        return $this->newChunk;
    }
}
