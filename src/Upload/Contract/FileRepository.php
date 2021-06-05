<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Contract;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface FileRepository
{
    public function findByIdentifier(string $identifier): ?File;
}
