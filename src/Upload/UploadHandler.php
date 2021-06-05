<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload;

use Illuminate\Http\Request;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface UploadHandler
{
    public function name(): string;

    public function getIdentifier(Request $request): string;

    public function getPart(Request $request): int;

    public function getTotalChunks(Request $request): int;

    public function getChunkSize(Request $request): int;

    public function getTotalSize(Request $request): int;

    public function getFileName(Request $request): string;

    public function isValidRequest(Request $request): bool;
}
