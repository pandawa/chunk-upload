<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem;

use League\Flysystem\Plugin\AbstractPlugin;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 *
 * @mixin AbstractPlugin
 */
trait SupportsChunkUpload
{
    public function supportsChunkUpload(): bool
    {
        if (!method_exists($this->filesystem, 'getAdapter')) {
            return false;
        }

        return $this->filesystem->getAdapter() instanceof ChunkStorage;
    }
}
