<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Handler;

use Illuminate\Http\Request;
use Pandawa\ChunkUpload\Upload\Contract\ChunkUploadPayload;
use Pandawa\ChunkUpload\Upload\UploadHandler;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class ResumableJsUploadHandler implements UploadHandler
{
    public function name(): string
    {
        return 'resumablejs';
    }

    public function getIdentifier(Request $request): string
    {
        return $request->input('resumableIdentifier');
    }

    public function getPart(Request $request): int
    {
        return (int)$request->input('resumableChunkNumber');
    }

    public function getTotalChunks(Request $request): int
    {
        return (int)$request->input('resumableTotalChunks');
    }

    public function getFileName(Request $request): string
    {
        return $request->input('resumableFilename');
    }

    public function getChunkSize(Request $request): int
    {
        return (int)$request->input('resumableChunkSize');
    }

    public function getTotalSize(Request $request): int
    {
        return (int)$request->input('resumableTotalSize');
    }

    public function getPayload(Request $request): ChunkUploadPayload
    {
        return new ChunkUploadPayload(
            identifier: $this->getIdentifier($request),
            part: $this->getPart($request),
            totalChunks: $this->getTotalChunks($request),
            fileName: $this->getFileName($request),
            chunkSize: $this->getChunkSize($request),
            totalSize: $this->getTotalSize($request)
        );
    }

    public function isValidRequest(Request $request): bool
    {
        $requiredFields = [
            'resumableChunkSize',
            'resumableTotalSize',
            'resumableFilename',
            'resumableTotalChunks',
            'resumableChunkNumber',
            'resumableIdentifier',
        ];
        $payload = $request->input();

        foreach ($requiredFields as $requiredField) {
            if (null === ($payload[$requiredField] ?? null)) {
                return false;
            }
        }

        return true;
    }
}
