<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Contract;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface File
{
    public function getIdentifier(): string;

    public function getReference(): string;

    public function getPath(): string;
}
