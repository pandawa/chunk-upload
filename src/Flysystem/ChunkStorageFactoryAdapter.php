<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface ChunkStorageFactoryAdapter
{
    public function create(array $config): ChunkStorage;
}
