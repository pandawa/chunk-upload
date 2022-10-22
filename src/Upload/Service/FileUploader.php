<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Service;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Pandawa\ChunkUpload\Upload\Contract\Chunked;
use Pandawa\ChunkUpload\Upload\Contract\ChunkUploadPayload;
use Pandawa\ChunkUpload\Upload\Contract\File as FileContract;
use Pandawa\ChunkUpload\Upload\Contract\FileRepository;
use Pandawa\ChunkUpload\Upload\Exception\UploadFileException;
use Pandawa\ChunkUpload\Upload\UploadManager;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FileUploader
{
    public function __construct(
        private readonly UploadManager $uploadManager,
        private readonly FileRepository $fileRepository,
        private readonly FilesystemManager $storage
    ) {}

    public function uploadFromRequest(Request $request, ?UploadedFile $chunk): Chunked
    {
        $chunkUploadPayload = $this->uploadManager->validateAndExtractRequest($request);
        $file = $this->findFile($chunkUploadPayload->identifier);

        if ($request->isMethod('GET')) {
            return new Chunked($file, $chunkUploadPayload->part);
        }

        if ($request->isMethod('POST') && null === $chunk) {
            throw new UploadFileException('Missing file.', 400);
        }

        return $this->upload(
            $file,
            $chunkUploadPayload,
            $chunk
        );
    }

    public function upload(FileContract $file, ChunkUploadPayload $payload, ?File $chunk): Chunked
    {
        $chunkPart = $payload->part;

        return new Chunked(
            $file,
            $chunkPart,
            $this->storage->drive($file->getDriver())->uploadPart(
                $file->getReference(),
                $file->getPath(),
                $chunkPart,
                $chunk
            ),
            $payload->totalChunks,
            true
        );
    }

    public function findFile(string $identifier): FileContract
    {
        if (null !== $file = $this->fileRepository->findByIdentifier($identifier)) {
            return $file;
        }

        throw new UploadException(sprintf('File with identifier "%s" is not found.', $identifier), 404);
    }
}
