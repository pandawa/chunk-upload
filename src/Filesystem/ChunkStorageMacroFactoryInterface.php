<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Filesystem;

use Closure;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface ChunkStorageMacroFactoryInterface
{
    public function uploadPart(): Closure;

    public function initiateMultipartUpload(): Closure;

    public function completeMultipartUpload(): Closure;

    public function abortMultipartUpload(): Closure;
}
